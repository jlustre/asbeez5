<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Session\Middleware\StartSession;
use App\Http\Controllers\POS\CustomersController;
use App\Http\Controllers\POS\CashiersController;

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
