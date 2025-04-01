<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'is_active'
    ];

    protected static function booted()
    {
        static::addGlobalScope('active', function ($query) {
            $query->where('is_active', true);
        });
    }

   
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

   
    public function user()
    {
        return $this->belongsTo(User::class);
    }

   
    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

   
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }


    public static function averageRating($productId)
    {
        return self::where('product_id', $productId)->avg('rating');
    }

    public static function ratingCountByStars($productId, $star)
    {
        return self::where('product_id', $productId)
                   ->where('rating', $star)
                   ->count();
    }
}
