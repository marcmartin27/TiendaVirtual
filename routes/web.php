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
use App\Http\Controllers\ChatbotController;
use App\Services\PayPalService;
use Illuminate\Http\Request;




Route::get('/', [HomeController::class, 'index']);
Route::get('/viewall', [ProductController::class, 'viewAll'])->name('viewall');
Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);
Route::get('/clear-login-flag', [App\Http\Controllers\AuthController::class, 'clearLoginFlag']);
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
Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');
Route::post('/order/process', [OrderController::class, 'process'])->name('order.process');
Route::get('/order/confirmation/{id}', [OrderController::class, 'confirmation'])->name('order.confirmation');
Route::get('/user-address/{userId}', [OrderController::class, 'getUserAddress']);
Route::get('/profile', [UserController::class, 'profile'])->name('profile');
Route::post('/updateProfile', [UserController::class, 'updateProfile'])->name('updateProfile');
Route::post('/chatbot/send', [ChatbotController::class, 'chat']);
Route::get('/juego-promocion', [App\Http\Controllers\GameController::class, 'index'])->name('game');
Route::post('/generate-coupon', [App\Http\Controllers\GameController::class, 'generateCoupon']);
Route::post('/products/{id}/stock', [ProductController::class, 'updateStock'])->name('products.updateStock');
Route::get('/products/{id}/stock', [ProductController::class, 'getStock'])->name('products.getStock');
Route::get('/check-stock/{productId}/{size}', function ($productId, $size) {
    $productSize = \App\Models\Size::where('product_id', $productId)
                                   ->where('size', $size)
                                   ->first();
                                   
    if (!$productSize) {
        return response()->json(['error' => 'Producto o talla no encontrada'], 404);
    }
    
    return response()->json(['stock' => $productSize->stock]);
});

Route::post('/order/process', [App\Http\Controllers\CheckoutController::class, 'procesarPedido'])->name('order.process');
Route::get('/paypal/pay', function (PayPalService $paypal) {
    $link = $paypal->createOrder(10); // Monto de 10 USD para pruebas
    if ($link) {
        return redirect($link);
    }
    return 'Ocurrió un error al crear la orden';
})->name('paypal.pay');

Route::get('/paypal/success', [App\Http\Controllers\CheckoutController::class, 'paypalSuccess'])->name('paypal.success');

Route::get('/paypal/cancel', [App\Http\Controllers\CheckoutController::class, 'paypalCancel'])->name('paypal.cancel');
