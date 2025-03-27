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

    // Verificar primero si el usuario está autenticado
    if (!Auth::check() && !$request->input('userId')) {
        return response()->json([
            'success' => false, 
            'message' => 'Debe iniciar sesión para añadir productos al carrito'
        ], 401);
    }
        $userId = $request->input('userId') ?: Auth::id();
        $cartItems = $request->input('cartItems', []);
        
        // Si el carrito está vacío, eliminar todos los elementos del carrito del usuario
        if (empty($cartItems)) {
            // Eliminar todos los productos del carrito de este usuario
            Cart::where('user_id', $userId)->delete();  // CORREGIDO: Cart en lugar de CartItem
            return response()->json(['message' => 'Carrito vaciado correctamente']);
        }
        
        // Resto del código sin cambios...
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
                'size' => $item['size'],
                'customized_image' => $item['isCustomized'] ? $item['image'] : null
            ]);
        }
    
        return response()->json(['message' => 'Carrito guardado correctamente']);
    }
    
    public function getCart($userId)
    {
        $cartItems = Cart::where('user_id', $userId)->get()->map(function ($cartItem) {
            $product = Product::find($cartItem->product_id);
            $image = $product->images->first();
            
            // Determinar el precio correcto
            $price = $product->new_price ? $product->new_price : $product->price;
            
            // Usar la imagen personalizada si existe
            $imageUrl = '';
            if ($cartItem->customized_image) {
                $imageUrl = $cartItem->customized_image;
                $isCustomized = true;
            } else if ($image) {
                // Si la imagen ya tiene una URL completa (http o https), usarla tal cual
                if (strpos($image->image_url, 'http') === 0) {
                    $imageUrl = $image->image_url;
                } else {
                    // Si es una ruta relativa, añadir la URL base
                    $imageUrl = asset('images/' . $image->image_url);
                }
                $isCustomized = false;
            }
            
            return [
                'id' => $cartItem->product_id,
                'name' => $product->name,
                'price' => $price,
                'quantity' => $cartItem->quantity,
                'size' => $cartItem->size,
                'image' => $imageUrl,
                'isCustomized' => $isCustomized
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