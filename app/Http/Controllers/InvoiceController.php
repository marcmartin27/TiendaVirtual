<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function generate($orderId)
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Obtener el pedido con sus items
        $order = Order::with('items.product')
                     ->where('user_id', Auth::id())
                     ->findOrFail($orderId);
        
        // Crear el PDF
        $pdf = PDF::loadView('invoices.template', [
            'order' => $order,
            'user' => Auth::user(),
        ]);
        
        // Personalizar el PDF
        $pdf->setPaper('a4');
        
        // Devolver el PDF para descarga
        return $pdf->download('factura-' . $order->id . '.pdf');
    }
}