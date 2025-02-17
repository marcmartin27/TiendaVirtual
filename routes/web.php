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
Route::get('/viewall', [ProductController::class, 'viewAll']);
Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::resource('users', UserController::class);
Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
Route::resource('orders', OrderController::class);
Route::resource('sizes', SizeController::class);
