<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Size;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'description', 'category_id', 'price', 'featured', 'sale', 'new_price'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function show($id) {
        $product = Product::with('images')->findOrFail($id); 
        return view('product', compact('product'));
    }

    public function sizes()
    {
        return $this->hasMany(Size::class);
    }
}