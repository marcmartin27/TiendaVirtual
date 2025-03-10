<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SizeController;



Route::get('/', [HomeController::class, 'index']);
Route::get('/viewall', [ProductController::class, 'viewAll'])->name('viewall');
Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::resource('users', UserController::class);
Route::resource('orders', OrderController::class);
Route::resource('sizes', SizeController::class);
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::post('/save-cart', [App\Http\Controllers\CartController::class, 'saveCart']);
Route::get('/get-cart/{userId}', [App\Http\Controllers\CartController::class, 'getCart']);
Route::get('/clear-login-flag', function() {
    session()->forget('just_logged_in');
    return response()->json(['success' => true]);
});
