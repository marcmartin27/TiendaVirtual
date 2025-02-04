<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('formularioProducto');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:products,code|max:10',
            'name' => 'required|max:255',
            'description' => 'required',
            'category_id' => 'required|integer',
            'price' => 'required|numeric',
            'featured' => 'boolean',
        ]);

        Product::create([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'featured' => $request->featured ? true : false,
        ]);

        return redirect()->route('products.create')->with('success', 'Producto añadido con éxito');
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

    public function viewAll()
    {
        // Aquí puedes obtener los productos de la base de datos y pasarlos a la vista
        return view('viewAll');
    }
}
