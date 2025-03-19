<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    
    protected $fillable = ['code', 'discount', 'valid_until', 'user_id', 'is_used'];
    
    protected $dates = ['valid_until'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}