<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Size;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->get();
        $categories = Category::all();
        return view('dashboard', compact('products', 'categories'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('formularioProducto', compact('categories'));
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
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
    
        try {
            $product = Product::create([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'featured' => $request->featured ? true : false,
            ]);
    
            // Manejar la carga de las imágenes
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('images'), $imageName);
    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $imageName,
                    ]);
                }
            }
    
            // Añadir tallas por defecto
            for ($size = 36; $size <= 47; $size++) {
                Size::create([
                    'size' => $size,
                    'product_id' => $product->id,
                    'stock' => 10,
                ]);
            }
    
            return redirect()->route('dashboard')->with('success', 'Producto añadido con éxito');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with(['images', 'sizes'])->find($id);

        // Obtener productos relacionados de la misma categoría (marca)
        $relatedProducts = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->inRandomOrder()
        ->take(4)
        ->get();

        return view('product', compact('product', 'relatedProducts'));

        if (!$product) {
            abort(404);
        }

        /* return view('product', compact('product')); */
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|max:10|unique:products,code,' . $id,
            'name' => 'required|max:255',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'images.*' => 'image',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/images');
                $product->images()->create(['image_url' => $path]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Producto actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('dashboard')->with('success', 'Producto eliminado con éxito');
    }
    public function viewAll(Request $request)
    {
        $categoryName = $request->query('category');
        $sale = $request->query('sale');
        
        if ($categoryName) {
            $category = Category::where('name', $categoryName)->first();

            if ($category) {
                $products = Product::where('category_id', $category->id)->get();
            } else {
                $products = collect(); // Empty collection if category not found
            }
        } elseif ($sale == "1"){
            $products = Product::where('sale', true)->get();
        } else {
            $products = Product::all();
        }
        return view('viewAll', compact('products'));
    }

    public function showProduct($id)
    {
        $product = Product::with(['category', 'images', 'sizes'])->findOrFail($id);
        return view('productDetail', compact('product'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'LIKE', "%{$query}%")
                            ->orWhere('description', 'LIKE', "%{$query}%")
                            ->with('images')
                            ->get();
    
        return response()->json($products);
    }

}
