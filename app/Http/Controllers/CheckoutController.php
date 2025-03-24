<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Size;
use App\Models\Product;
use App\Models\Cart;

class CheckoutController extends Controller
{
    public function index()
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            // Si no está autenticado, redirigir a la página principal con un parámetro
            return redirect('/')->with('showLogin', true)->with('loginMessage', 'Debes iniciar sesión para finalizar tu compra.');
        }
        
        // Verificar stock antes de mostrar la página de checkout
        $stockCheck = $this->verificarStockUsuario();
        
        // Si hay productos sin stock, redirigir al carrito con mensaje de error
        if (!$stockCheck['valid']) {
            return redirect('/cart')->with('error', $stockCheck['message']);
        }
        
        // Si se ajustaron cantidades, mostrar mensaje de alerta
        if (isset($stockCheck['adjusted']) && $stockCheck['adjusted']) {
            session()->flash('warning', $stockCheck['message']);
        }
        
        // Si está autenticado y el stock es válido, mostrar la página de finalizarCompra
        return view('finalizarCompra');
    }
    
    // Método para verificar stock del usuario actual
    private function verificarStockUsuario()
    {
        $userId = Auth::id();
        $cartItems = Cart::where('user_id', $userId)->get();
        $adjustments = [];
        $stockAdjusted = false;
        
        foreach ($cartItems as $item) {
            $size = Size::where('product_id', $item->product_id)
                        ->where('size', $item->size)
                        ->first();
            
            // Si no existe la talla o no hay stock
            if (!$size) {
                return [
                    'valid' => false,
                    'message' => "Producto no disponible en la talla seleccionada"
                ];
            }
            
            // Si la cantidad excede el stock disponible
            if ($size->stock < $item->quantity) {
                $product = Product::find($item->product_id);
                $productName = $product ? $product->name : 'Producto';
                
                // Si no hay stock en absoluto
                if ($size->stock <= 0) {
                    return [
                        'valid' => false,
                        'message' => "El producto {$productName} (talla {$item->size}) ya no está disponible"
                    ];
                }
                
                // Guardar información para el mensaje de ajuste
                $adjustments[] = [
                    'name' => $productName,
                    'size' => $item->size,
                    'oldQuantity' => $item->quantity,
                    'newQuantity' => $size->stock
                ];
                
                // Ajustar la cantidad al stock disponible
                $item->quantity = $size->stock;
                $item->save();
                $stockAdjusted = true;
            }
        }
        
        if ($stockAdjusted) {
            $message = "Se han ajustado algunas cantidades debido a limitaciones de stock:\n";
            foreach ($adjustments as $adj) {
                $message .= "- \"{$adj['name']}\" (talla {$adj['size']}): cambio de {$adj['oldQuantity']} a {$adj['newQuantity']} unidades\n";
            }
            
            return [
                'valid' => true,
                'adjusted' => true,
                'message' => $message
            ];
        }
        
        return ['valid' => true, 'adjusted' => false, 'message' => ''];
    }
    
    // Método para procesar el pedido
    public function procesarPedido(Request $request)
    {
        // Verificar stock antes de procesar el pedido
        $stockCheck = $this->verificarStockUsuario();
        
        if (!$stockCheck['valid']) {
            // Si hay problemas de stock, devolver error
            return back()->with('error', $stockCheck['message']);
        }
        
        // Aquí iría el resto del código para procesar el pedido
        // - Crear pedido en la base de datos
        // - Reducir stock de productos
        // - Limpiar carrito
        // - Etc.
        
        // Redirigir a página de éxito
        return redirect('/pedido-completado')->with('success', 'Tu pedido ha sido procesado correctamente');
    }
}