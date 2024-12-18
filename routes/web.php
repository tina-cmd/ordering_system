<?php

use App\Http\Controllers\CashierController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MenuController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// for authenticated admin

// Group routes with the same middleware
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/menu', [AdminController::class, 'menu'])->name('menu');
    Route::get('/inventory', [InventoryController::class, 'inventory'])->name('inventory');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');

    Route::post('/menu/add', [MenuController::class, 'menu_add'])->name('menu.add');
    Route::post('/menu/update', [MenuController::class, 'menu_update'])->name('menu.update');
    Route::delete('/menu/delete/', [MenuController::class, 'destroy'])->name('delete-menu');
    Route::post('/inventory/update', [InventoryController::class, 'inventory_update'])->name('inventory.updte');
});


// orthers for kitchen side
Route::get('/orders', [OrderController::class, 'get_order'])->name('order');
Route::post('/order/update', [OrderController::class, 'update_order'])->name('update.order');


//cashier side
Route::get('/cashier', [CashierController::class, 'cashier'])->name('cashier');
Route::post('/cashier/paid', [CashierController::class, 'pay'])->name('pay');









require __DIR__.'/auth.php';
