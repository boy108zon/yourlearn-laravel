<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Arr;
use App\Services\ProductImageService;

class Product extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'products';
    
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($product) {
            $imageService = new ProductImageService();
            foreach ($product->images as $image) {
                if ($image->image_url !== null) {
                    $imageService->deleteImage($image->image_url);
                }
                if ($image->thumbnail_url !== null) {
                    $imageService->deleteImage($image->thumbnail_url);
                }
            }
            $product->images()->delete();
        });
    }

    protected $fillable = [
        'name','description','slug','price', 'stock_quantity', 'weight','average_rating','rating_count', 'sku', 'is_active'
    ];

    protected static $auditInclude = [
        'stock_quantity',
        'price',         
    ];

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
    
    public function transformAudit(array $data): array
    {
        Arr::set($data, 'custom',  json_encode(array('order_id'=> session('order_id'))));
        return $data;
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'price')->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id')->using(CategoryProduct::class);
    }

    public function promoCodes()
    {
        return $this->belongsToMany(PromoCodes::class, 'promo_code_product', 'product_id', 'promo_code_id');
    }


    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_items')
                    ->withPivot('quantity', 'price') 
                    ->withTimestamps();
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function getHighestPromoCodeDiscount()
    {

        $productPrice = $this->price;
        $promoCodes = $this->promoCodes()->where('is_active', 1)->get();
    
        if ($promoCodes->isEmpty()) {
            return ['percentage_value' => 0, 'discountValue' => 0,'promo_code'=>0];
        }

        $discountDetails = $promoCodes->map(function ($promoCode) use ($productPrice) {
            if ($promoCode->discount_type === 'percentage') {
                $discountValue = ($promoCode->discount_amount / 100) * $productPrice;
                return [
                    'percentage_value' => $promoCode->discount_amount,
                    'discountValue' => $discountValue,
                    'promo_code'=>$promoCode->code
                ];
            } else {
                return [
                    'percentage_value' => 0, 
                    'discountValue' => $promoCode->discount_amount,
                    'promo_code'=>$promoCode->code
                ]; 
            }
        });

        $maxDiscount = $discountDetails->sortByDesc('discountValue')->first();
        return $maxDiscount;
    }

    
}   
