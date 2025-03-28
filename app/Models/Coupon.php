<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'code', 'discount', 'valid_until', 'user_id', 'is_used'
    ];
    
    protected $casts = [
        'valid_until' => 'datetime',
        'is_used' => 'boolean',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function isValid()
    {
        return !$this->is_used && $this->valid_until->greaterThanOrEqualTo(now());
    }
}