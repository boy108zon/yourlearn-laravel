<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','cart_id','guest_id','email','first_name','last_name','total_price','status', 'shipping_address', 'billing_address', 'payment_method', 'tracking_number'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price','discount_amount')->withTimestamps();
    }

    
    public function cart()
    {
        return $this->hasOne(Cart::class, 'id', 'cart_id'); 
    }

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            if (!$order->user_id) {
                $order->guest_id = session()->getId();
            }
        });
    }
}
