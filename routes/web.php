<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/viewall', [ProductController::class, 'viewAll']);
Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');