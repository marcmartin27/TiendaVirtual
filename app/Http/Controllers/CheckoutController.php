<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            // Si no está autenticado, redirigir a la página principal con un parámetro
            return redirect('/')->with('showLogin', true)->with('loginMessage', 'Debes iniciar sesión para finalizar tu compra.');
        }
        
        // Si está autenticado, mostrar la página de finalizarCompra (no checkout)
        return view('finalizarCompra');
    }
}