<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $products = Product::all();
        $categories = Category::all();
        $orders = Order::all();
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalOrders = Order::count();

        return view('dashboard', compact('users', 'products', 'categories', 'orders', 'totalUsers', 'totalProducts', 'totalCategories', 'totalOrders'));
    }

    public function show()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
                       ->orderBy('created_at', 'desc')
                       ->with('items') // Eager loading para evitar múltiples consultas
                       ->get();
        
        return view('profile', compact('user', 'orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('dashboard')->with('success', 'Usuario añadido con éxito');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('dashboard')->with('success', 'Usuario actualizado con éxito');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('dashboard')->with('success', 'Usuario eliminado con éxito');
    }

    public function profile()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->with('items') // Eager loading para evitar múltiples consultas
                ->get();
        
        return view('profile', compact('user', 'orders'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->address = $request->input('address');
        $user->apartment = $request->input('apartment');
        $user->postal_code = $request->input('postal_code');
        $user->city = $request->input('city');
        $user->province = $request->input('province');
        $user->country = $request->input('country');
        $user->phone = $request->input('phone');
        $user->save();

        return redirect()->back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        // Verificar que la contraseña actual es correcta
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('password_error', 'La contraseña actual no es correcta.');
        }
        
        // Actualizar la contraseña
        $user->password = Hash::make($request->new_password);
        $user->save();
        
        return redirect()->back()->with('password_success', 'Contraseña actualizada correctamente.');
    }
}