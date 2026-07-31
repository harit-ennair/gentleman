<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Client;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public Home Route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Category, Product, Service Public access
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/categories/{category}/products', [CategoryController::class, 'products'])->name('categories.products');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

// Breeze dashboard view
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Authenticated Routes
Route::middleware([])->group(function () {
    // Breeze Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Client Appointments
    Route::get('/appointments/available-slots', [Client\AppointmentController::class, 'availableSlots'])->name('appointments.available-slots');
    Route::get('/appointments', [Client\AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/appointments', [Client\AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{appointment}', [Client\AppointmentController::class, 'show'])->name('appointments.show');
    Route::post('/appointments/{appointment}/cancel', [Client\AppointmentController::class, 'cancel'])->name('appointments.cancel');

    // Client Orders
    Route::get('/orders', [Client\OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [Client\OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [Client\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [Client\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}/invoice', [Client\OrderController::class, 'invoice'])->name('orders.invoice');

    // Shopping Cart
    Route::get('/cart', [Client\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [Client\CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update', [Client\CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [Client\CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [Client\CartController::class, 'clear'])->name('cart.clear');

    // Admin-only Routes
    Route::middleware([])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // User management
        Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [Admin\UserController::class, 'show'])->name('users.show');
        Route::put('/users/{user}', [Admin\UserController::class, 'update'])->name('users.update');
        Route::post('/users/{user}/toggle-status', [Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');

        // Admin Appointments management
        Route::get('/appointments', [Admin\AppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/{appointment}', [Admin\AppointmentController::class, 'show'])->name('appointments.show');
        Route::post('/appointments/{appointment}/confirm', [Admin\AppointmentController::class, 'confirm'])->name('appointments.confirm');
        Route::post('/appointments/{appointment}/complete', [Admin\AppointmentController::class, 'complete'])->name('appointments.complete');
        Route::post('/appointments/{appointment}/cancel', [Admin\AppointmentController::class, 'cancel'])->name('appointments.cancel');

        // Admin Orders management
        Route::get('/orders', [Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');
        Route::post('/orders/{order}/cancel', [Admin\OrderController::class, 'cancel'])->name('orders.cancel');

        // Admin Categories CRUD
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Admin Products CRUD/Toggle
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::post('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');

        // Admin Services CRUD/Toggle/Appointments view
        Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
        Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
        Route::post('/services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
        Route::get('/services/{service}/appointments', [ServiceController::class, 'appointments'])->name('services.appointments');
    });
});

require __DIR__.'/auth.php';
