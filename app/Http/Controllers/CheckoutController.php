<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Size;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\PayPalService;
use App\Models\Coupon;

class CheckoutController extends Controller
{
    protected $paypalService;
    
    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

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
    
    // Método para verificar stock del usuario actual con ajuste automático
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
    
    // Método para procesar la compra con PayPal
    public function procesarPedido(Request $request)
    {
        // Guardar la información del formulario en sesión
        $request->session()->put('checkout_data', $request->all());
        
        // Capturar explícitamente el valor del descuento y el código del cupón
        $discountValue = floatval($request->input('discount_value', 0));
        $appliedCoupon = $request->input('applied_coupon', '');
        
        // Calcular el total del carrito
        $userId = Auth::id();
        $cartItems = Cart::where('user_id', $userId)
                        ->with('product')
                        ->get();
        
        $total = 0;
        foreach ($cartItems as $item) {
            $product = Product::find($item->product_id);
            $price = $product->sale ? $product->new_price : $product->price;
            $total += $price * $item->quantity;
        }
        
        // Calcular el total final con descuento
        $finalTotal = $total - $discountValue;
        
        // No permitir totales negativos
        $finalTotal = max(0, $finalTotal);
        
        // Guardar los valores correctamente en la sesión
        $request->session()->put('order_total', $finalTotal);
        $request->session()->put('discount_value', $discountValue);
        $request->session()->put('applied_coupon', $appliedCoupon);
        
        // Crear orden de PayPal con el total DESPUÉS del descuento
        $paypalLink = $this->paypalService->createOrder($finalTotal);
        
        if (!$paypalLink) {
            return back()->with('error', 'Error al conectar con PayPal. Por favor, inténtalo de nuevo.');
        }
        
        return redirect($paypalLink);
    }
    
    // Método para manejar el retorno exitoso de PayPal
    public function paypalSuccess(Request $request)
    {
        $orderId = $request->query('token');
        $result = $this->paypalService->captureOrder($orderId);
        
        if (!$result || $result->status !== 'COMPLETED') {
            return redirect('/checkout')->with('error', 'El pago no pudo ser procesado. Por favor, inténtalo de nuevo.');
        }
        
        // Recuperar datos del formulario de checkout
        $checkoutData = $request->session()->get('checkout_data', []);
        $orderTotal = $request->session()->get('order_total', 0);
        
        // Recuperar datos del cupón
        $appliedCoupon = $request->session()->get('applied_coupon', '');
        $discountAmount = $request->session()->get('discount_value', 0);
        
        // Calcular el subtotal (lo que habría costado sin descuento)
        $subtotal = $orderTotal + $discountAmount;
        
        // Crear la orden en nuestra base de datos
        $order = new Order();
        $order->user_id = Auth::id();
        $order->total = $orderTotal;
        $order->status = 'Paid';
        $order->payment_method = 'paypal';
        $order->payment_id = $orderId;
        
        // Añadir información del descuento
        $order->discount_amount = $discountAmount;  // Asegurarse que este valor se guarda correctamente
        $order->coupon_code = $appliedCoupon;
        
        // Datos de envío
        $order->first_name = $checkoutData['first_name'] ?? '';
        $order->last_name = $checkoutData['last_name'] ?? '';
        $order->email = $checkoutData['email'] ?? Auth::user()->email;
        $order->address = $checkoutData['address'] ?? '';
        $order->apartment = $checkoutData['apartment'] ?? '';
        $order->city = $checkoutData['city'] ?? '';
        $order->postal_code = $checkoutData['postal_code'] ?? '';
        $order->province = $checkoutData['province'] ?? '';
        $order->country = $checkoutData['country'] ?? '';
        $order->phone = $checkoutData['phone'] ?? '';
        
        $order->save();
        
        // Si se aplicó un cupón, marcarlo como utilizado
        if (!empty($appliedCoupon)) {
            $coupon = Coupon::where('code', $appliedCoupon)->first();
            if ($coupon) {
                $coupon->is_used = true;
                $coupon->save();
            }
        }
        
        // Guardar los items de la orden
        $cartItems = Cart::where('user_id', Auth::id())->get();
        
        foreach ($cartItems as $item) {
            $product = Product::find($item->product_id);
            $price = $product->sale ? $product->new_price : $product->price;
            
            // Obtener el size_id correspondiente
            $size = Size::where('product_id', $item->product_id)
                        ->where('size', $item->size)
                        ->first();
            
            
            // Crear item de la orden
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item->product_id;
            $orderItem->product_name = $product->name;
            $orderItem->price = $price;
            $orderItem->quantity = $item->quantity;
            $orderItem->size = $item->size;
            $orderItem->size_id = $size->id;
            $orderItem->total_price = $price * $item->quantity; // Calcular y asignar el precio total
            $orderItem->save();
            
            // Actualizar stock
            $size->stock -= $item->quantity;
            $size->save();
        }
        
        // Vaciar el carrito
        Cart::where('user_id', Auth::id())->delete();
        
        // Limpiar datos de sesión
        $request->session()->forget([
            'checkout_data', 
            'order_total', 
            'applied_coupon',
            'discount_amount'
        ]);
        
        // Redireccionar a la página de confirmación
        return redirect()->route('order.confirmation', $order->id);
    }
    
    // Método para manejar cancelación de PayPal
    public function paypalCancel()
    {
        return redirect()->route('checkout')
                         ->with('error', 'El pago fue cancelado. Por favor, inténtalo de nuevo.');
    }
}