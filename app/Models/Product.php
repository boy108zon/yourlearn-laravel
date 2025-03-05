<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Arr;

class Product extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'products';
    
    protected $fillable = [
        'name', 'description', 'price', 'stock_quantity', 'weight', 'sku', 'image_url', 'is_active'
    ];

    protected static $auditInclude = [
        'stock_quantity',
        'price',         
    ];

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

    

}   
