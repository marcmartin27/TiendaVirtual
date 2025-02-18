<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Size;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::all();
        return view('sizes.index', compact('sizes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'size' => 'required|integer',
            'product_id' => 'required|exists:products,id',
            'stock' => 'required|integer',
        ]);

        Size::create($request->all());

        return redirect()->route('sizes.index')->with('success', 'Talla añadida con éxito');
    }

    public function edit($id)
    {
        $size = Size::findOrFail($id);
        return response()->json($size);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'size' => 'required|integer',
            'product_id' => 'required|exists:products,id',
            'stock' => 'required|integer',
        ]);

        $size = Size::findOrFail($id);
        $size->update($request->all());

        return redirect()->route('sizes.index')->with('success', 'Talla actualizada con éxito');
    }

    public function destroy($id)
    {
        $size = Size::findOrFail($id);
        $size->delete();

        return redirect()->route('sizes.index')->with('success', 'Talla eliminada con éxito');
    }
}