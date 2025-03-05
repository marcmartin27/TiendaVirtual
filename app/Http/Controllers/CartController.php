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

        // Eliminar los productos actuales del carrito del usuario
        Cart::where('user_id', $userId)->delete();

        // Guardar los nuevos productos en el carrito
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
}