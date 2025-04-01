<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'buyer_id',
        'total',
        'status',
        'order_date',
        'shipping_date',
        'shipping_address',
        'payment_method',
        'payment_id',
        'first_name',
        'last_name',
        'email',
        'address',
        'apartment',
        'city',
        'postal_code',
        'province',
        'country',
        'phone'
    ];

    // Relación con Buyer (el destinatario real del pedido)
    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    // Relación con User (el usuario que realizó el pedido)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con los items del pedido
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    protected $casts = [
        'shipping_address' => 'array'
    ];
}