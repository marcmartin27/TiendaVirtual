<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Coupon;
use Illuminate\Support\Str;

class GameController extends Controller
{
    public function index()
    {
        return view('game');
    }
    
    public function generateCoupon(Request $request)
    {
        // Verificar si el usuario ha ganado legítimamente
        if (!$request->has('score') || $request->score < 100) {
            return response()->json(['success' => false, 'message' => 'Puntuación insuficiente']);
        }
        
        // Generar código de cupón único
        $couponCode = 'SNEAKS10-' . strtoupper(Str::random(8));
        
        // Guardar el cupón en la base de datos
        $coupon = new Coupon();
        $coupon->code = $couponCode;
        $coupon->discount = 10; // 10% de descuento
        $coupon->valid_until = now()->addDays(30);
        
        // Si el usuario está autenticado, asociar el cupón a su cuenta
        if (Auth::check()) {
            $coupon->user_id = Auth::id();
        }
        
        $coupon->save();
        
        return response()->json([
            'success' => true,
            'couponCode' => $couponCode,
            'message' => '¡Felicidades! Has ganado un cupón del 10% de descuento'
        ]);
    }
}