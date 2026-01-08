<?php

if (! function_exists('base62_alphabet')) {
    function base62_alphabet(): string
    {
        return '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    }
}

if (! function_exists('base62_encode')) {
    function base62_encode(int $num): string
    {
        if ($num === 0) {
            return '0';
        }

        $alphabet = base62_alphabet();
        $base = 62;
        $result = '';

        // Handle unsigned values safely
        if ($num < 0) {
            throw new \InvalidArgumentException('Negative numbers are not supported');
        }

        while ($num > 0) {
            $idx = $num % $base;
            $result = $alphabet[$idx] . $result;
            $num = intdiv($num, $base);
        }

        return $result;
    }
}

if (! function_exists('base62_decode')) {
    function base62_decode(string $str): ?int
    {
        $alphabet = base62_alphabet();
        $base = 62;
        $map = array_flip(str_split($alphabet));

        $str = trim($str);
        if ($str === '') {
            return 0;
        }

        $num = 0;
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            $ch = $str[$i];
            if (! isset($map[$ch])) {
                return null; // invalid character
            }
            $num = $num * $base + $map[$ch];
        }

        return $num;
    }
}

if (! function_exists('hash_id')) {
    /**
     * Obfuscate an integer id into a 10-character reversible string.
     *
     * @param int|string $id
     * @return string 10-character hash
     */
    function hash_id(int|string $id): string
    {
        $id = (int) $id;
        if ($id < 0) {
            throw new \InvalidArgumentException('ID must be non-negative');
        }

        $salt = env('ID_HASH_SECRET', 'asbeez-secret');
        $mask = (int) (crc32($salt) & 0xFFFFFFFF);

        // Mix with mask to avoid exposing raw sequence
        $mixed = $id ^ $mask;

        $encoded = base62_encode($mixed);

        // We reserve 2 chars for checksum, so max payload is 8
        if (strlen($encoded) > 8) {
            throw new \InvalidArgumentException('ID too large to hash within 10 characters');
        }

        $payload = str_pad($encoded, 8, '0', STR_PAD_LEFT);

        // 2-char base62 checksum over payload + salt
        $val = crc32($payload . $salt) % (62 * 62);
        $c1 = intdiv($val, 62);
        $c0 = $val % 62;
        $alphabet = base62_alphabet();
        $checksum = $alphabet[$c1] . $alphabet[$c0];

        return $payload . $checksum;
    }
}

if (! function_exists('unhash_id')) {
    /**
     * Reverse the 10-character hash back to the original id.
     * Returns null if the hash is invalid or checksum fails.
     *
     * @param string $hash
     * @return int|null
     */
    function unhash_id(string $hash): ?int
    {
        $hash = trim($hash);
        if (strlen($hash) !== 10) {
            return null;
        }

        $salt = env('ID_HASH_SECRET', 'asbeez-secret');
        $alphabet = base62_alphabet();

        $payload = substr($hash, 0, 8);
        $checksum = substr($hash, -2);

        // Validate checksum
        $val = crc32($payload . $salt) % (62 * 62);
        $c1 = intdiv($val, 62);
        $c0 = $val % 62;
        $expected = $alphabet[$c1] . $alphabet[$c0];
        if ($checksum !== $expected) {
            return null;
        }

        $mixed = base62_decode(ltrim($payload, '0'));
        if ($mixed === null) {
            return null;
        }

        $mask = (int) (crc32($salt) & 0xFFFFFFFF);
        $id = $mixed ^ $mask;

        return $id;
    }
}
