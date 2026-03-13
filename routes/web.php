<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Root: redirect to dashboard (auth middleware will redirect to login if unauthenticated)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Guest routes (cannot access if already logged in)
Route::middleware('guest')->group(function () {
    Route::get('/login', App\Livewire\Login::class)->name('login');
});

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->middleware('auth')->name('logout');

// Protected routes (must be logged in)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', App\Livewire\Dashboard::class)->name('dashboard');
    Route::get('/users', App\Livewire\UserManagement::class)->name('users');
    Route::get('/settings', App\Livewire\Settings::class)->name('settings');
    Route::get('/products', App\Livewire\ProductManagement::class)->name('products');
    Route::get('/products/create', App\Livewire\ProductCreate::class)->name('products.create');
    Route::get('/products/{id}/edit', App\Livewire\ProductEdit::class)->name('products.edit');
    Route::get('/pos', App\Livewire\Pos::class)->name('pos');
    Route::get('/sales', App\Livewire\Sales::class)->name('sales');
    Route::get('/stock', App\Livewire\StockManagement::class)->name('stock');
    Route::get('/debts', App\Livewire\DebtManagement::class)->name('debts');
    Route::get('/reports', App\Livewire\Reports::class)->name('reports');
});
