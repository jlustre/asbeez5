<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VendorController as AdminVendorController;
use App\Http\Controllers\Admin\UserAddressController as AdminUserAddressController;
use App\Http\Controllers\User\ProfilePhotoController;
use App\Http\Controllers\User\ProfileBackgroundController;
use App\Http\Controllers\User\ProfileBirthdateController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/about', function () {
    return view('about.about-us');
})->name('about');

Route::get('/faq', function () {
    return view('faq.faq');
})->name('faq');

Route::get('/contact', function () {
    return view('contact.contact');
})->name('contact');

// Point of Sale landing (guest accessible)
Route::view('/pos', 'pos.index')->name('pos');
// POS auth endpoints
Route::post('/pos/login', [\App\Http\Controllers\POS\AuthController::class, 'login'])->name('pos.login');
Route::get('/pos/logout', [\App\Http\Controllers\POS\AuthController::class, 'logout'])->name('pos.logout');
// POS protected screens
Route::view('/pos/home', 'pos.home')->middleware('pos.auth')->name('pos.home');
Route::view('/pos/register', 'pos.register')->middleware('pos.auth')->name('pos.register');

Route::view('dashboard', 'user.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Seller login alias -> uses Fortify's login
Route::middleware('guest')->get('/seller/login', function (Request $request) {
    // After successful login, send sellers to dashboard (adjust if you add a seller area)
    if (! session()->has('url.intended')) {
        session(['url.intended' => route('seller.dashboard')]);
    }
    return redirect()->route('login');
})->name('seller.login');

// Admin login alias -> uses Fortify's login, sets intended to admin dashboard
Route::middleware('guest')->get('/admin/login', function (Request $request) {
    if (! session()->has('url.intended')) {
        session(['url.intended' => route('admin.dashboard')]);
    }
    return redirect()->route('login');
})->name('admin.login');

// Seller routes
Route::prefix('seller')->name('seller.')->middleware(['auth', 'verified', 'can:seller-only'])->group(function () {
    Route::view('dashboard', 'seller.dashboard')->name('dashboard');
    // Add more seller routes here, e.g.:
    // Route::get('orders', [SellerOrderController::class, 'index'])->name('orders.index');
    // Route::get('products', [SellerProductController::class, 'index'])->name('products.index');
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'can:admin-only'])->group(function () {
    Route::view('dashboard', 'admin.dashboard')->name('dashboard');
    Route::resource('users', AdminUserController::class);
    Route::resource('vendors', AdminVendorController::class);
    Route::resource('employees', \App\Http\Controllers\Admin\EmployeeController::class);
    Route::resource('business-categories', \App\Http\Controllers\Admin\BusinessCategoryController::class);
    Route::resource('branches', \App\Http\Controllers\Admin\BranchController::class);
    Route::resource('branch-units', \App\Http\Controllers\Admin\BranchUnitController::class);
    // User addresses management
    Route::post('users/{user}/addresses', [AdminUserAddressController::class, 'store'])->name('users.addresses.store');
    Route::put('users/{user}/addresses/{address}', [AdminUserAddressController::class, 'update'])->name('users.addresses.update');
    Route::post('users/{user}/addresses/{address}/default', [AdminUserAddressController::class, 'setDefault'])->name('users.addresses.set-default');
    Route::delete('users/{user}/addresses/{address}', [AdminUserAddressController::class, 'destroy'])->name('users.addresses.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    // Profile photo upload route
    Route::post('settings/profile/photo', [ProfilePhotoController::class, 'store'])
        ->name('profile.photo.store');

    // Avatar background update route
    Route::post('settings/profile/avatar-background', [ProfileBackgroundController::class, 'store'])
        ->name('profile.background.store');

    // Profile birthdate update
    Route::post('settings/profile/birthdate', [ProfileBirthdateController::class, 'store'])
        ->name('profile.birthdate.store');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

// Fallback route for unmatched URLs -> return custom 404 page
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
