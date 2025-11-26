<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\SalaryController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'resetPasswordDirectly'])->name('password.direct.reset');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Order status check (public)
Route::get('/check-order', [OrderController::class, 'checkStatus'])->name('orders.check');

// Protected routes
Route::middleware('auth')->group(function () {
    // CRUD RESOURCE ROUTES
    Route::resource('services', ServiceController::class)->except(['show']);
    Route::resource('orders', OrderController::class)->except(['show']);
    Route::resource('promotions', PromotionController::class)->except(['show']);
    // Route::resource('expenses', ExpenseController::class)->except(['show']);


    // User dashboard
    Route::get('/dashboard', [OrderController::class, 'index'])->name('dashboard');
    
    // Order routes
    Route::resource('orders', OrderController::class);
    Route::post('/orders/{order}/upload-payment', [OrderController::class, 'uploadPaymentProof'])->name('orders.upload-payment');
    Route::post('/admin/orders/{order}/upload-view-proof', [OrderController::class, 'uploadViewProof'])->name('admin.orders.upload-view-proof');
    
    
    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        // Static admin order creation routes must come before the dynamic {order} route
        Route::get('/orders/create-manual', [AdminController::class, 'createManualOrder'])->name('orders.create-manual');
        Route::post('/orders/create-manual', [AdminController::class, 'storeManualOrder'])->name('orders.store-manual');
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
    Route::delete('/orders/{order}', [AdminController::class, 'destroyOrder'])->name('orders.destroy');
    Route::get('/orders/{order}/print', [AdminController::class, 'printOrder'])->name('orders.print');
    Route::post('/orders/{order}/update-services', [AdminController::class, 'updateOrderServices'])->name('orders.update-services');
        Route::post('/orders/{order}/update-status', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');
        Route::post('/orders/{order}/verify-payment', [AdminController::class, 'verifyPayment'])->name('orders.verify-payment');
        Route::post('/orders/{order}/upload-view-proof', [AdminController::class, 'uploadViewProof'])->name('orders.upload-view-proof');
        
        // Expense routes
        Route::resource('expenses', ExpenseController::class, ['except' => ['show']]);

        // Salary routes
        Route::resource('salaries', SalaryController::class, ['except' => ['show']]);
        
        // Admin Data routes (Atun only)
        Route::prefix('data')->name('data.')->group(function () {
            Route::get('/', [App\Http\Controllers\AdminDataController::class, 'index'])->name('index');
            Route::get('/orders', [App\Http\Controllers\AdminDataController::class, 'orders'])->name('orders');
            Route::get('/expenses', [App\Http\Controllers\AdminDataController::class, 'expenses'])->name('expenses');
            Route::get('/promotions', [App\Http\Controllers\AdminDataController::class, 'promotions'])->name('promotions');
            Route::get('/reports', [App\Http\Controllers\AdminDataController::class, 'reports'])->name('reports');
        });
    });
});
