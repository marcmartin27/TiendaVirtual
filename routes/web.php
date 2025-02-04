<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/viewall', [ProductController::class, 'viewAll']);
Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);

