<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Session\Middleware\StartSession;
use App\Http\Controllers\POS\CustomersController;
use App\Http\Controllers\POS\CashiersController;
use App\Http\Controllers\Api\V1\OrdersController as V1OrdersController;
use App\Http\Controllers\Api\V1\PaymentsController as V1PaymentsController;

// Start sessions for POS API endpoints without enforcing CSRF
Route::prefix('pos')->middleware([StartSession::class])->group(function () {
    // Customers
    Route::get('customers', [CustomersController::class, 'index']);
    Route::post('customers', [CustomersController::class, 'store']);

    // Cashiers
    Route::get('cashiers', [CashiersController::class, 'index']);
    Route::post('cashiers/switch', [CashiersController::class, 'switch']);
    Route::get('session', [CashiersController::class, 'session']);
});

// Public API v1 (device/HQ integration)
Route::prefix('v1')->group(function () {
    // Orders
    Route::get('orders', [V1OrdersController::class, 'index']);
    Route::get('orders/{public_id}', [V1OrdersController::class, 'show']);
    Route::post('orders', [V1OrdersController::class, 'store'])->middleware('idempotency.require');

    // Payments
    Route::post('orders/{public_id}/payments', [V1PaymentsController::class, 'store'])->middleware('idempotency.require');
});
