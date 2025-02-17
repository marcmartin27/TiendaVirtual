<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'buyer_id', 'status', 'order_date', 'shipping_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}