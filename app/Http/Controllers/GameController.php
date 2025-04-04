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
        // Verificación simple - solo permitir acceso si está autenticado
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Debes iniciar sesión para jugar y obtener cupones de descuento.');
        }
        
        return view('game');
    }
    
    public function generateCoupon(Request $request)
    {
        // Verificar si el usuario ha ganado legítimamente
        if (!$request->has('score') || $request->score < 100) {
            return response()->json(['success' => false, 'message' => 'Puntuación insuficiente']);
        }
        
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'notAuthenticated' => true,
                'message' => 'Debes iniciar sesión para obtener un cupón de descuento'
            ]);
        }
        
        $userId = Auth::id();
        
        // Comprobar si el usuario ya tiene un cupón activo (no caducado)
        $activeCoupon = Coupon::where('user_id', $userId)
            ->where('valid_until', '>', now())
            ->orderBy('valid_until', 'desc')
            ->first();
        
        if ($activeCoupon) {
            // Calcular días restantes
            $daysRemaining = ceil(now()->diffInDays($activeCoupon->valid_until));
            
            return response()->json([
                'success' => false,
                'hasActiveCoupon' => true,
                'daysRemaining' => $daysRemaining,
                'couponCode' => $activeCoupon->code,
                'message' => "Ya has reclamado un cupon. Debes esperar {$daysRemaining} días para obtener uno nuevo."
            ]);
        }
        
        // Generar código de cupón único
        $couponCode = 'SNEAKS10-' . strtoupper(Str::random(8));
        
        // Guardar el cupón en la base de datos
        $coupon = new Coupon();
        $coupon->code = $couponCode;
        $coupon->discount = 10; // 10% de descuento
        $coupon->valid_until = now()->addDays(30);
        $coupon->user_id = $userId;
        $coupon->save();
        
        return response()->json([
            'success' => true,
            'couponCode' => $couponCode,
            'message' => '¡Felicidades! Has ganado un cupón del 10% de descuento'
        ]);
    }
}