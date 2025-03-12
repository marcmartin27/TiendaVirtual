<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function saveCart(Request $request)
    {
        $userId = $request->userId;
        $cartItems = $request->cartItems;
        
        // Log para debug
        \Log::info('Recibiendo carrito para guardar:', [
            'userId' => $userId,
            'itemCount' => count($cartItems ?? [])
        ]);
    
        if (!$userId || !$cartItems) {
            return response()->json(['error' => 'Datos inválidos'], 400);
        }
    
        // Primero eliminar el carrito existente
        Cart::where('user_id', $userId)->delete();
    
        // Luego guardar los nuevos items
        foreach ($cartItems as $item) {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'size' => $item['size']
            ]);
        }
    
        return response()->json(['message' => 'Carrito guardado correctamente']);
    }

    public function getCart($userId)
    {
        $cartItems = Cart::where('user_id', $userId)->get()->map(function ($cartItem) {
            $product = Product::find($cartItem->product_id);
            $image = $product->images->first();
            
            // Determinar el precio correcto (usar new_price si está disponible)
            $price = $product->new_price ? $product->new_price : $product->price;
            
            // Construir la URL completa de la imagen
            $imageUrl = '';
            if ($image) {
                // Si la imagen ya tiene una URL completa (http o https), usarla tal cual
                if (strpos($image->image_url, 'http') === 0) {
                    $imageUrl = $image->image_url;
                } 
                // Si es una ruta relativa, añadir la URL base
                else {
                    $imageUrl = asset('images/' . $image->image_url);
                }
            } else {
                // Imagen por defecto si no hay imágenes asociadas
                $imageUrl = asset('images/default-product.png');
            }
            
            return [
                'id' => $cartItem->product_id,
                'name' => $product->name,
                'price' => $price,
                'quantity' => $cartItem->quantity,
                'size' => $cartItem->size,
                'image' => $imageUrl
            ];
        });

        return response()->json(['cartItems' => $cartItems]);
    }

    public function redirectToCheckout()
    {
        if (Auth::check()) {
            return redirect()->route('checkout');
        } else {
            // Guardar que el usuario quería ir a checkout para redirigirlo después del login
            session(['redirect_after_login' => 'checkout']);
            return redirect()->route('login')->with('message', 'Por favor inicia sesión para finalizar la compra');
        }
    }
}