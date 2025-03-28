<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function validate(Request $request)
    {
        $code = $request->input('code');
        
        // Buscar el cupón por código
        $coupon = Coupon::where('code', $code)->first();
        
        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Código de cupón no válido'
            ]);
        }
        
        // Comprobar si el cupón está expirado
        if ($coupon->valid_until->lessThan(now())) {
            return response()->json([
                'valid' => false,
                'message' => 'Este cupón ha expirado'
            ]);
        }
        
        // Comprobar si el cupón ya ha sido usado
        if ($coupon->is_used) {
            return response()->json([
                'valid' => false,
                'message' => 'Este cupón ya ha sido utilizado'
            ]);
        }
        
        // Comprobar si el cupón pertenece a un usuario específico
        if ($coupon->user_id && $coupon->user_id != Auth::id()) {
            return response()->json([
                'valid' => false,
                'message' => 'Este cupón no está asignado a tu cuenta'
            ]);
        }
        
        // El cupón es válido
        return response()->json([
            'valid' => true,
            'message' => 'Cupón válido',
            'discount' => $coupon->discount,
            'type' => 'percentage' // Asumimos que todos son porcentajes según tu esquema
        ]);
    }
}