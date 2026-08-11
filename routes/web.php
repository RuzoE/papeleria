<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ─── Auth routes (guest only) ───────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ─── Protected routes ────────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ── Phase 2: Categories, Locations, Suppliers
    Route::patch('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);

    Route::resource('locations', LocationController::class)->except(['create', 'show', 'edit']);

    Route::patch('suppliers/{supplier}/toggle-status', [SupplierController::class, 'toggleStatus'])->name('suppliers.toggle-status');
    Route::resource('suppliers', SupplierController::class)->except(['create', 'show', 'edit']);

    // ── Phase 3: Products (routes will be added here)

    // ── Phase 4: Inventory Movements (routes will be added here)

    // ── Phase 5: Purchases (routes will be added here)

    // ── Phase 6: Sales (routes will be added here)

    // ── Phase 7: Transactions (routes will be added here)

    // ── Phase 8: Cash Register (routes will be added here)

    // ── Phase 9: Reports (routes will be added here)

    // ── Phase 10: Users, Audit (routes will be added here)

});

