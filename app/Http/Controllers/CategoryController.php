<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('dashboard', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:categories,code|max:10',
            'name' => 'required|max:255',
        ]);

        Category::create([
            'code' => $request->code,
            'name' => $request->name,
        ]);

        return redirect()->route('dashboard')->with('success', 'Categoría añadida con éxito');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|max:10|unique:categories,code,' . $id,
            'name' => 'required|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'code' => $request->code,
            'name' => $request->name,
        ]);

        return redirect()->route('dashboard')->with('success', 'Categoría actualizada con éxito');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('dashboard')->with('success', 'Categoría eliminada con éxito');
    }
}