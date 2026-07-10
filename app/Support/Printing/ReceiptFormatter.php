<?php

namespace App\Support\Printing;

use App\Models\Order;
use Illuminate\Support\Collection;

class ReceiptFormatter
{
    public static function jobForOrder(Order $order, Collection $items, array $opts = []): array
    {
        $store = (string) ($opts['store_name'] ?? config('app.name', 'ASBEEZ'));
        $branch = $opts['branch_code'] ?? null;
        $register = $opts['register_code'] ?? null;
        $cashier = $opts['cashier_name'] ?? null;

        $headerLine = trim(implode('  ', array_filter([
            $branch ? "Branch $branch" : null,
            $register ? "Reg $register" : null,
            $cashier ? "Cashier $cashier" : null,
        ])));

        $content = [];
        $content[] = ['op' => 'text', 'data' => $store, 'align' => 'center', 'bold' => true];
        if ($headerLine !== '') {
            $content[] = ['op' => 'text', 'data' => $headerLine, 'align' => 'center'];
        }
        $content[] = ['op' => 'kv', 'k' => 'Order#', 'v' => str_pad((string)$order->order_number, 6, '0', STR_PAD_LEFT)];
        $content[] = ['op' => 'rule'];

        foreach ($items as $it) {
            $name = ($it->qty ?? 1) . '  ' . $it->name;
            $content[] = ['op' => 'row', 'cols' => [
                ['text' => $name],
                ['text' => self::money($it->total_cents), 'align' => 'right']
            ]];
        }

        $content[] = ['op' => 'rule'];
        $content[] = ['op' => 'kv', 'k' => 'SUBTOTAL', 'v' => self::money($order->subtotal_cents)];
        if ((int) $order->discount_cents > 0) {
            $content[] = ['op' => 'kv', 'k' => 'DISCOUNT', 'v' => self::money($order->discount_cents)];
        }
        $content[] = ['op' => 'kv', 'k' => 'TAX', 'v' => self::money($order->tax_cents)];
        $content[] = ['op' => 'total', 'k' => 'TOTAL', 'v' => self::money($order->total_cents), 'emphasis' => true];
        $content[] = ['op' => 'text', 'data' => 'Thank you!', 'align' => 'center'];
        $content[] = ['op' => 'cut'];

        return [
            'type' => 'receipt',
            'profile' => 'receipt_80mm_default',
            'copies' => 1,
            'content' => $content,
        ];
    }

    private static function money(int $cents): string
    {
        return number_format($cents / 100, 2, '.', '');
    }
}
