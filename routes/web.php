<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\OrderController;

// Locale Switch Route
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');


// authentication pages
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showSignin'])->name('signin');
    Route::post('/signin', [AuthController::class, 'signin'])->name('signin.post');

    Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'signup'])->name('signup.post');
});

// ── Logout ──────────────────────────────────────────────────
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ── Admin ────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        // Data Unit
        Route::resource('units', UnitController::class);

        // Data Customer
        Route::resource('customers', CustomerController::class)->except(['show']);
        Route::patch(
            'customers/{customer}/toggle-active',
            [CustomerController::class, 'toggleActive']
        )->name('customers.toggle-active');
        
        //Data Driver
        Route::resource('drivers', DriverController::class)->except(['show']);
        
        // Orders
        Route::resource('orders', OrderController::class)->only(['index', 'show']);
        Route::post('orders/{order}/approve', [OrderController::class, 'approve'])->name('orders.approve');
        Route::post('orders/{order}/reject',  [OrderController::class, 'reject'])->name('orders.reject');
    });

// ── Driver ───────────────────────────────────────────────────
Route::middleware(['auth', 'role:driver'])
    ->prefix('driver')
    ->name('driver.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'driver'])->name('dashboard');
    });

// ── Customer ─────────────────────────────────────────────────
Route::middleware(['auth', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'customer'])->name('dashboard');
    });



// // dashboard pages
// Route::get('/dashboard', function () {
//     return view('pages.dashboard.ecommerce', ['title' => 'E-commerce Dashboard']);
// })->name('dashboard');

// calender pages




















