<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCodes extends Model
{
    use HasFactory;

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
    
    protected $fillable = [
        'code', 
        'discount_type', 
        'discount_amount', 
        'start_date', 
        'end_date', 
        'is_active'
    ];

    
    public function products()
    {
        return $this->belongsToMany(Product::class, 'promo_code_product', 'promo_code_id', 'product_id');
    }
    
    public function isActive()
    {
        return $this->is_active && now()->between($this->start_date, $this->end_date);
    }

    public function calculateDiscount($price)
    {
        if ($this->discount_type === 'percentage') {
            return $price * ($this->discount_amount / 100);
        }elseif ($this->discount_type === 'fixed') {
            return $this->discount_amount;
        }else{
            return 0;
        }
    }
}
