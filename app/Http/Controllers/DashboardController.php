<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\DB; 

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalOrders = Order::count();

        // Datos para el gráfico de productos por mes
        $productsByMonth = DB::table('products')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereRaw('YEAR(created_at) = YEAR(CURDATE())')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Datos para el gráfico de pedidos por mes
        $ordersByMonth = DB::table('orders')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereRaw('YEAR(created_at) = YEAR(CURDATE())')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();
        
        // Nombres de los meses para el eje X
        $months = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        // Rellenar los meses faltantes con ceros
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($productsByMonth[$i])) {
                $productsByMonth[$i] = 0;
            }
            if (!isset($ordersByMonth[$i])) {
                $ordersByMonth[$i] = 0;
            }
        }
        
        // Ordenar arrays por clave (mes)
        ksort($productsByMonth);
        ksort($ordersByMonth);

        // Obtener todos los productos, categorías, usuarios y pedidos para las tablas
        $products = Product::all();
        $categories = Category::all();
        $users = User::all();
        $orders = Order::all();

        return view('dashboard', compact(
            'totalUsers', 'totalProducts', 'totalCategories', 'totalOrders',
            'productsByMonth', 'ordersByMonth', 'months',
            'products', 'categories', 'users', 'orders'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
