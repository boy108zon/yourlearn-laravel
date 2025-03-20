<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'session_id','discount_amount','applied_discount_type','applied_discount','promo_code','status'];

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_ABANDONED = 'abandoned';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Cart is in progress.',
            self::STATUS_COMPLETED => 'Cart has been checked out.',
            self::STATUS_ABANDONED => 'Cart was abandoned.',
        ];
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isAbandoned()
    {
        return $this->status === self::STATUS_ABANDONED;
    }

    public function setStatusPending()
    {
        $this->status = self::STATUS_PENDING;
        $this->save();
    }

    public function setStatusCompleted()
    {
        $this->status = self::STATUS_COMPLETED;
        $this->save();
    }

    public function setStatusAbandoned()
    {
        $this->status = self::STATUS_ABANDONED;
        $this->save();
    }
   
    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getTotalPrice()
    {
        return $this->items->sum(function($item) {
            return $item->quantity * $item->price;
        });
    }

    public static function getCart($cartId)
    {
        return self::with('items.product')->findOrFail($cartId);
    }

    public static function getOrCreateCart($userId = null, $sessionId = null)
    {
        return self::firstOrCreate([
            'user_id' => $userId,
            'session_id' => $sessionId,
        ]);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart_items')
                    ->withPivot('quantity', 'price') 
                    ->withTimestamps();
    }

}
