<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function checkout()
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            // Guardar que el usuario quería ir a checkout para redirigirlo después del login
            session(['redirect_after_login' => 'checkout']);
            return redirect()->route('login')->with('message', 'Por favor inicia sesión para finalizar la compra');
        }
        
        return view('finalizarCompra');
    }
    
    public function process(Request $request)
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Validar datos del formulario
        $validatedData = $request->validate([
            'email' => 'required|email',
            'country' => 'required|string|max:2',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'apartment' => 'nullable|string|max:255',
            'postal_code' => 'required|string|max:10',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);
        
        // Actualizar información del usuario
        $user = Auth::user();
        $user->first_name = $validatedData['first_name'];
        $user->last_name = $validatedData['last_name'];
        $user->address = $validatedData['address'];
        $user->apartment = $validatedData['apartment'];
        $user->postal_code = $validatedData['postal_code'];
        $user->city = $validatedData['city'];
        $user->province = $validatedData['province'];
        $user->phone = $validatedData['phone'];
        $user->country = $validatedData['country'];
        $user->save();
        
        // Obtener datos del carrito desde la petición
        $cartItems = json_decode($request->cartData, true);
        
        if (empty($cartItems)) {
            return redirect()->back()->with('error', 'El carrito está vacío');
        }
        
        // Calcular total
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        // Crear el pedido
        $order = new Order();
        $order->user_id = $user->id;
        $order->total = $total;
        $order->status = 'pending'; // Estado inicial
        $order->shipping_address = json_encode([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'address' => $validatedData['address'],
            'apartment' => $validatedData['apartment'],
            'postal_code' => $validatedData['postal_code'],
            'city' => $validatedData['city'],
            'province' => $validatedData['province'],
            'country' => $validatedData['country'],
            'phone' => $validatedData['phone'],
        ]);
        $order->save();
        
        // Guardar los items del pedido
        foreach ($cartItems as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item['id'];
            $orderItem->quantity = $item['quantity'];
            $orderItem->price = $item['price'];
            $orderItem->size = $item['size'];
            $orderItem->save();
        }
        
        // Limpiar el carrito (se hará desde JS)
        
        return redirect()->route('order.confirmation', $order->id)
            ->with('success', 'Tu pedido ha sido realizado correctamente');
    }
    
    public function confirmation($id)
    {
        $order = Order::findOrFail($id);
        
        // Verificar que el usuario sea el propietario del pedido
        if (Auth::id() != $order->user_id) {
            abort(403, 'No tienes permiso para ver este pedido');
        }
        
        return view('orderConfirmation', compact('order'));
    }
    
    public function getUserAddress($userId)
    {
        // Verificar que el usuario actual coincida con el solicitado
        if (Auth::id() != $userId) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }
        
        $user = User::find($userId);
        
        return response()->json([
            'success' => true,
            'user' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'address' => $user->address,
                'apartment' => $user->apartment,
                'postal_code' => $user->postal_code,
                'city' => $user->city,
                'province' => $user->province,
                'phone' => $user->phone,
                'country' => $user->country,
            ]
        ]);
    }
}