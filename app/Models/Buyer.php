<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    protected $fillable = [
        'user_id', 'email', 'phone', 'first_name', 'last_name', 
        'address', 'apartment', 'postal_code', 'city', 'province', 'country'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}