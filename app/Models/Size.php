<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Size extends Model
{
    use HasFactory;

    protected $fillable = [
        'size', 'product_id', 'stock'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}