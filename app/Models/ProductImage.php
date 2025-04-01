<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'image_url','thumbnail_url','is_active','is_primary'];

    protected $casts = [
        'is_primary' => 'integer', 
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
