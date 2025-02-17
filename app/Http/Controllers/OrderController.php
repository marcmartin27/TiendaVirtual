<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->get();
        $users = User::all();
        return view('dashboard', compact('orders', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'buyer_id' => 'required|exists:users,id',
            'status' => 'required|string|max:255',
        ]);

        Order::create($request->all());

        return redirect()->route('dashboard')->with('success', 'Pedido añadido con éxito');
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'buyer_id' => 'required|exists:users,id',
            'status' => 'required|string|max:255',
        ]);

        $order = Order::findOrFail($id);
        $order->update($request->all());

        return redirect()->route('dashboard')->with('success', 'Pedido actualizado con éxito');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('dashboard')->with('success', 'Pedido eliminado con éxito');
    }
}