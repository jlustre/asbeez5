<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Support\Outbox;
use App\Models\OrderItem;
use App\Support\Printing\ReceiptFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentsController extends Controller
{
    public function store(Request $request, string $publicId)
    {
        $data = $request->validate([
            'method' => 'required|in:cash,card,qr,wallet,other',
            'amount_cents' => 'required|integer|min:0',
            'provider' => 'nullable|string|max:64',
            'provider_txn_id' => 'nullable|string|max:100',
        ]);

        $order = Order::query()->where('public_id', $publicId)->firstOrFail();

        $result = DB::transaction(function () use ($order, $data) {
            $paymentPublicId = (string) Str::ulid();
            $change = 0;
            $status = 'captured';
            if ($data['method'] === 'cash') {
                $change = max(0, (int) $data['amount_cents'] - (int) $order->total_cents);
            }

            $paymentId = DB::table('payments')->insertGetId([
                'public_id' => $paymentPublicId,
                'client_request_id' => request('client_request_id'),
                'order_id' => $order->id,
                'method' => $data['method'],
                'amount_cents' => (int) $data['amount_cents'],
                'change_cents' => $change,
                'provider' => $data['provider'] ?? null,
                'provider_txn_id' => $data['provider_txn_id'] ?? null,
                'status' => $status,
                'captured_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Mark order paid if fully covered (simplified)
            DB::table('orders')->where('id', $order->id)->update(['status' => 'paid', 'updated_at' => now()]);

            // Receipt numbering per register/date
            $receiptDate = now()->toDateString();
            $maxNum = DB::table('receipts')
                ->where('register_id', $order->register_id)
                ->where('receipt_date', $receiptDate)
                ->max('receipt_number');
            $receiptNumber = (int) $maxNum + 1;

            // Build receipt payload snapshot
            $items = OrderItem::query()->where('order_id', $order->id)->orderBy('line_number')->get();
            $job = ReceiptFormatter::jobForOrder($order, $items, []);

            $receiptId = DB::table('receipts')->insertGetId([
                'order_id' => $order->id,
                'register_id' => $order->register_id,
                'receipt_number' => $receiptNumber,
                'receipt_date' => $receiptDate,
                'is_reprint' => false,
                'reprint_of' => null,
                'payload' => json_encode($job),
                'printed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Outbox::enqueue('payment', $paymentId, 'payment.captured', [
                'order_public_id' => $order->public_id,
                'method' => $data['method'],
                'amount_cents' => (int) $data['amount_cents'],
                'change_cents' => $change,
            ], [
                'register_id' => $order->register_id,
            ]);

            Outbox::enqueue('order', $order->id, 'order.paid', [
                'public_id' => $order->public_id,
                'total_cents' => $order->total_cents,
            ]);

            // Enqueue print job for Edge
            Outbox::enqueue('receipt', $receiptId, 'print.receipt', [
                'job' => $job,
                'order_public_id' => $order->public_id,
            ], [
                'register_id' => $order->register_id,
            ]);

            return [
                'payment_public_id' => $paymentPublicId,
                'status' => $status,
                'change_cents' => $change,
                'receipt_number' => $receiptNumber,
                'queued_print' => true,
            ];
        });

        return response()->json($result, 201);
    }
}
