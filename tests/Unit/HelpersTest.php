<?php

test('hash_id produces 10 characters and is reversible', function () {
    $ids = [0, 1, 42, 123456, 987654321];
    foreach ($ids as $id) {
        $hash = hash_id($id);
        expect($hash)->toHaveLength(10);
        $decoded = unhash_id($hash);
        expect($decoded)->toBe($id);
    }
});

test('unhash_id returns null for invalid checksum', function () {
    $hash = hash_id(1234);
    // Tweak last char to break checksum
    $bad = substr($hash, 0, 9) . (substr($hash, -1) === 'A' ? 'B' : 'A');
    expect(unhash_id($bad))->toBeNull();
});

// This test documents behavior when ids are too large for 10-char limit
// Adjust if you change the encoding capacity.
// 62^8 - 1 ~= 218,340,105,584,895
// We force an exception if payload exceeds 8 chars.

test('hash_id throws for very large ids', function () {
    $tooLarge = 218340105584896; // one more than 62^8 - 1
    expect(fn () => hash_id($tooLarge))->toThrow(InvalidArgumentException::class);
});
