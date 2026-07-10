<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Support\Outbox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();
        if ($request->filled('status')) $query->where('status', $request->string('status'));
        if ($request->filled('branch_id')) $query->where('branch_id', (int) $request->input('branch_id'));
        $per = min(max((int) $request->input('per_page', 50), 1), 200);
        $orders = $query->orderByDesc('id')->paginate($per);
        return response()->json([
            'data' => $orders->items(),
            'meta' => ['page' => $orders->currentPage(), 'per_page' => $orders->perPage(), 'total' => $orders->total()],
            'links' => ['next' => $orders->nextPageUrl()]
        ]);
    }

    public function show(string $publicId)
    {
        $order = Order::query()->where('public_id', $publicId)->firstOrFail();
        $items = OrderItem::query()->where('order_id', $order->id)->orderBy('line_number')->get();
        $latestReceipt = \Illuminate\Support\Facades\DB::table('receipts')
            ->where('order_id', $order->id)
            ->orderByDesc('id')
            ->first();
        return response()->json([
            'public_id' => $order->public_id,
            'status' => $order->status,
            'totals' => [
                'subtotal_cents' => $order->subtotal_cents,
                'discount_cents' => $order->discount_cents,
                'tax_cents' => $order->tax_cents,
                'total_cents' => $order->total_cents,
            ],
            'items' => $items,
            'payments' => [],
            'latest_receipt' => $latestReceipt ? [
                'receipt_number' => $latestReceipt->receipt_number,
                'printed_at' => $latestReceipt->printed_at,
            ] : null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'context.branch_id' => 'required|integer',
            'context.branch_unit_id' => 'required|integer',
            'context.register_id' => 'nullable|integer',
            'context.employee_id' => 'nullable|integer',
            'order.type' => 'nullable|in:dine_in,takeaway,delivery',
            'order.customer_id' => 'nullable|integer',
            'order.items' => 'required|array|min:1',
            'order.items.*.sku' => 'required|string|max:64',
            'order.items.*.name' => 'required|string|max:200',
            'order.items.*.qty' => 'nullable|numeric|min:0.001',
            'order.items.*.unit_price_cents' => 'required|integer|min:0',
            'order.items.*.discount_cents' => 'nullable|integer|min:0',
            'order.items.*.tax_rate_bp' => 'required|integer|min:0',
            'order.items.*.modifiers' => 'nullable|array',
            'order.notes' => 'nullable|string|max:500',
        ]);

        $branchId = (int) data_get($data, 'context.branch_id');
        $branchUnitId = (int) data_get($data, 'context.branch_unit_id');
        $registerId = data_get($data, 'context.register_id');
        $employeeId = data_get($data, 'context.employee_id');

        $publicId = (string) Str::ulid();
        $orderDate = now()->toDateString();

        $order = DB::transaction(function () use ($data, $branchId, $branchUnitId, $registerId, $employeeId, $publicId, $orderDate) {
            $maxNum = DB::table('orders')
                ->where('branch_id', $branchId)
                ->where('order_date', $orderDate)
                ->max('order_number');
            $orderNumber = (int) $maxNum + 1;

            $totals = [
                'subtotal' => 0,
                'discount' => 0,
                'tax' => 0,
                'total' => 0,
            ];

            $orderId = DB::table('orders')->insertGetId([
                'public_id' => $publicId,
                'client_request_id' => $request->input('client_request_id'),
                'branch_id' => $branchId,
                'branch_unit_id' => $branchUnitId,
                'register_id' => $registerId,
                'employee_id' => $employeeId,
                'order_number' => $orderNumber,
                'order_date' => $orderDate,
                'type' => data_get($data, 'order.type', 'takeaway'),
                'status' => 'awaiting_payment',
                'customer_id' => data_get($data, 'order.customer_id'),
                'subtotal_cents' => 0,
                'discount_cents' => 0,
                'tax_cents' => 0,
                'total_cents' => 0,
                'loyalty_earned' => 0,
                'loyalty_redeemed' => 0,
                'notes' => data_get($data, 'order.notes'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $line = 0;
            $insertedItems = [];
            foreach (data_get($data, 'order.items', []) as $item) {
                $line++;
                $qty = (float) ($item['qty'] ?? 1);
                $unit = (int) $item['unit_price_cents'];
                $disc = (int) ($item['discount_cents'] ?? 0);
                $rate = (int) $item['tax_rate_bp'];

                $lineBase = (int) round($qty * $unit);
                $lineDiscount = min($disc, $lineBase);
                $taxable = $lineBase - $lineDiscount;
                $lineTax = (int) round($taxable * $rate / 10000);
                $lineTotal = $taxable + $lineTax;

                $orderItemId = DB::table('order_items')->insertGetId([
                    'order_id' => $orderId,
                    'line_number' => $line,
                    'product_id' => null,
                    'sku' => $item['sku'],
                    'name' => $item['name'],
                    'qty' => $qty,
                    'unit_price_cents' => $unit,
                    'discount_cents' => $lineDiscount,
                    'tax_rate_bp' => $rate,
                    'tax_cents' => $lineTax,
                    'total_cents' => $lineTotal,
                    'route_station' => $item['route_station'] ?? null,
                    'status' => 'new',
                    'notes' => $item['notes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $insertedItems[] = [
                    'id' => $orderItemId,
                    'route_station' => $item['route_station'] ?? null,
                    'qty' => $qty,
                ];
                $totals['subtotal'] += $lineBase;
                $totals['discount'] += $lineDiscount;
                $totals['tax'] += $lineTax;
                $totals['total'] += $lineTotal;
            }

            DB::table('orders')->where('id', $orderId)->update([
                'subtotal_cents' => $totals['subtotal'],
                'discount_cents' => $totals['discount'],
                'tax_cents' => $totals['tax'],
                'total_cents' => $totals['total'],
            ]);

            // Create kitchen tickets by station
            $todayStart = now()->startOfDay();
            $todayEnd = now()->endOfDay();
            $byStation = collect($insertedItems)->groupBy('route_station')->filter(fn($g, $k) => $k);
            foreach ($byStation as $station => $rows) {
                $maxTicket = DB::table('kitchen_tickets')
                    ->where('station', $station)
                    ->whereBetween('routed_at', [$todayStart, $todayEnd])
                    ->max('ticket_number');
                $ticketNumber = (int) $maxTicket + 1;
                $ktId = DB::table('kitchen_tickets')->insertGetId([
                    'order_id' => $orderId,
                    'ticket_number' => $ticketNumber,
                    'station' => $station,
                    'status' => 'queued',
                    'routed_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                foreach ($rows as $r) {
                    DB::table('kitchen_ticket_items')->insert([
                        'kitchen_ticket_id' => $ktId,
                        'order_item_id' => $r['id'],
                        'qty' => $r['qty'],
                        'status' => 'queued',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                Outbox::enqueue('kitchen', $ktId, 'kitchen.ticket.created', [
                    'station' => $station,
                    'ticket_number' => $ticketNumber,
                ]);
            }

            $order = Order::find($orderId);
            Outbox::enqueue('order', $order->id, 'order.created', [
                'public_id' => $order->public_id,
                'totals' => [
                    'subtotal_cents' => $order->subtotal_cents,
                    'discount_cents' => $order->discount_cents,
                    'tax_cents' => $order->tax_cents,
                    'total_cents' => $order->total_cents,
                ],
            ], [
                'branch_id' => $branchId,
                'register_id' => $registerId,
            ]);

            return $order;
        });

        return response()->json([
            'public_id' => $order->public_id,
            'order_number' => $order->order_number,
            'order_date' => $order->order_date,
            'totals' => [
                'subtotal_cents' => $order->subtotal_cents,
                'tax_cents' => $order->tax_cents,
                'total_cents' => $order->total_cents,
                'discount_cents' => $order->discount_cents,
            ],
            'status' => $order->status,
        ], 201);
    }
}
