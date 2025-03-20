<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\PromoCodes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class CartService
{
    
    public function getCart()
    {
        $session_cart_id = session()->getId();
        if (Auth::check()) {
            $cart = Cart::with('items')->where('user_id', Auth::id())->where('status', '!=', 'completed')->firstOrCreate(['user_id' => Auth::id()]);
            if (session()->has('session_cart_id')) {
                $sessionCart = Cart::where('session_id', session()->get('session_cart_id'))->where('status', '!=', 'completed')->first();
                if ($sessionCart) {
                    CartItem::where('cart_id', $sessionCart->id)->update(['cart_id' => $cart->id]); 
                }
                session()->forget('session_cart_id');
            }
        } else {
            $cart = Cart::with('items')->where('session_id', $session_cart_id)->where('status', '!=', 'completed')->firstOrCreate(['session_id' => $session_cart_id]);
            session(['session_cart_id' => $session_cart_id]);
        }

        if ($cart->status === 'completed') {
            $cart->promo_code = null;
            $cart->discount_amount = 0;
        }

        return $cart;
    }

    public function addProductToCart(int $productId, int $quantity): bool{

        return DB::transaction(function () use ($productId, $quantity) {
            $product = Product::find($productId);
            
            if (!$product) {
                return false;  
            }

            if ($quantity <= 0 || $product->stock_quantity < $quantity) {
                return false;
            }

            $cart = $this->getCart();
            $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $product->id)->first();
            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->save();
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);
            }
            return true;
        });
    }

    public function removeCartItem($cartId, $productId)
    {
        $cart = Cart::find($cartId);
        if (!$cart) {
            return false;
        }
        $cartItem = $cart->products()->where('product_id', $productId)->first();
        if ($cartItem) {
            $cart->products()->detach($productId);
            if ($cart->products()->count() === 0) {
                $cart->delete();
            }
            return true;
        }

        return false;
    }

    public function clearCart(int $cartId)
    {
        $cart = Cart::find($cartId);
        if ($cart) {
            CartItem::where('cart_id', $cartId)->delete();
        }
    }

    public function getTotalPrice(): float
    {
        $cart = $this->getCart();
       
        $total = $cart->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return $total;
    }

    public function getCartContents()
    {
        $cart = $this->getCart();
       
        $cartItems = $cart->items()->with(['product:id,name,image_url,price,description,sku,weight,stock_quantity'])->get();
        $promoCode = $cart->promo_code;  
        $discountAmount = $cart->discount_amount ?? 0; 
        $applied_discount_type=$cart->applied_discount_type;
        $applied_discount=$cart->applied_discount;
        $cartId = $cart->id;  
        return $cartItems = $cartItems->map(function ($cartItem) use ($promoCode, $discountAmount,$cartId,$applied_discount_type,$applied_discount) {
            if ($cartItem->product) {
                $cartItem->product_image = $cartItem->product->image_url;
                $cartItem->product_name = $cartItem->product->name;  
                $cartItem->product_price = $cartItem->product->price; 
                $cartItem->product_description = $cartItem->product->description; 
                $cartItem->promo_code = $promoCode;
                $cartItem->discount_amount = $discountAmount;
                $cartItem->quantity = $cartItem->quantity; 
                $cartItem->product_sku = $cartItem->product->sku; 
                $cartItem->product_weight = $cartItem->product->weight; 
                $cartItem->stock_quantity= $cartItem->product->stock_quantity;
                $cartItem->cart_id=$cartId;
                $cartItem->applied_discount_type=$applied_discount_type;
                $cartItem->applied_discount=$applied_discount;
                
            } else {
                $cartItem->product_image = null;
                $cartItem->product_name = null;
                $cartItem->product_price = null;
                $cartItem->product_description = null;
                $cartItem->promo_code = null;
                $cartItem->discount_amount = null;
                $cartItem->quantity = 0;
                $cartItem->product_sku=null;
                $cartItem->product_weight = null; 
                $cartItem->stock_quantity=0;
                $cartItem->cart_id=0;
                $cartItem->applied_discount_type=null;
                $cartItem->applied_discount=0;
            }
            return $cartItem;
        });
    }

    public function updateCartItem(int $productId, int $quantity): bool
    {
        if ($quantity <= 0) {
            return false;
        }

        $cart = $this->getCart();
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();
       
        if ($cartItem) {
            $cartItem->quantity = $quantity;
            $cartItem->save();
            return true;
        }

        return false; 
    }


    public function processCheckout(array $checkoutData)
    {
        DB::beginTransaction();

        try {
            $cart = $this->getCart();

            if ($cart->items->isEmpty()) {
                return ['status' => 'error', 'message' => 'Cart is empty.'];
            }

            $first_name = $checkoutData['first_name'] ?? null;
            $last_name = $checkoutData['last_name'] ?? null;
            $email = $checkoutData['email'] ?? null;
            $paymentMethod = $checkoutData['payment_method'] ?? null;
            $shippingAddress = $checkoutData['shipping_address'] ?? null;
            $billingAddress = $checkoutData['billing_address'] ?? null;
            $country = $checkoutData['country'] ?? null;
            $state = $checkoutData['state'] ?? null;
            $zip = $checkoutData['zip'] ?? null;
            $promoCode = $checkoutData['promo_code'] ?? null;

            if (!$paymentMethod || !$shippingAddress || !$billingAddress || !$country || !$state || !$zip) {
                return ['status' => 'error', 'message' => 'All shipping, billing details, and payment method are required.'];
            }

            $totalPrice = $this->getTotalPrice();
            $totalPriceAfterDisCount = ($totalPrice - $cart->discount_amount);

            if ($totalPrice < 0) {
                $totalPrice = 0;
            }

            foreach ($cart->items as $cartItem) {
                $product = Product::lockForUpdate()->find($cartItem->product_id);

                if (!$product) {
                    return ['status' => 'error', 'message' => "Product not found: {$cartItem->product_id}"];
                }

                if ($product->stock_quantity < $cartItem->quantity) {
                    return ['status' => 'error', 'message' => "Not enough stock for product {$product->name}. Available stock: {$product->stock_quantity}"];
                }

                $product->decrement('stock_quantity', $cartItem->quantity);
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'cart_id' => $cart->id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'shipping_address' => $shippingAddress,
                'billing_address' => $billingAddress,
                'payment_method' => $paymentMethod,
                'total_price' => $totalPriceAfterDisCount,
                'status' => 'pending',
            ]);

            session(['order_id' => $order->id]);

            $order->tracking_number = $this->generateTrackingNumber($order->id);
            $order->save();

            foreach ($cart->items as $cartItem) {
                $order->products()->attach(
                    $cartItem->product_id,
                    ['quantity' => $cartItem->quantity, 'price' => $cartItem->price,'discount_amount'=> $cartItem->discount_amount]
                );
            }

            
            $cart->status = 'completed';
            $cart->save(); 

            CartItem::where('cart_id', $cart->id)->delete();
            
            DB::commit();
            return ['status' => 'success', 'order' => $order];

        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    private function generateTrackingNumber($orderId) {
        $randomString = time();
        $trackingNumber = "TRK-{$orderId}-{$randomString}";
        return $trackingNumber;
    }

    public function applyPromoCode11(string $promoCode)
    {
        $today = Carbon::now(); 
        $promo = PromoCodes::where('code', $promoCode)
            ->where('is_active', 1) 
            ->where('start_date', '<=', $today->toDateTimeString())
            ->where('end_date', '>=', $today->toDateTimeString())
            ->first();
        
        if (!$promo) {
            return ['status' => 'error', 'message' => 'Invalid or expired promo code.'];
        }

        $cart = $this->getCart();
        $cartItems = $cart->items; 
        
        $validProducts = $promo->products->pluck('id')->toArray();
        $cartProductIds = $cart->items->pluck('product_id')->toArray();

        $validCartItems = array_intersect($validProducts, $cartProductIds);

        if (empty($validCartItems)) {
            return ['status' => 'error', 'message' => 'Promo code does not apply to the products in your cart.'];
        }

        if ($promo->discount_type == 'percentage') {
            $discountAmount = ($cartItems[0]->price * ($promo->discount_amount / 100));
            $totalAfterDiscount = $cartItems[0]->price - $discountAmount;
        } else {
            $totalAfterDiscount = $promo->discount_amount;
        }

        $cart->promo_code = $promoCode;
        $cart->discount_amount = $totalAfterDiscount;
        $cart->applied_discount=$promo->discount_amount;
        $cart->applied_discount_type=$promo->discount_type;
        $cart->save();

        return [
            'status' => 'success',
            'message' => 'Promo code apply to the products in your cart.',
            'discount_amount' => $totalAfterDiscount,
        ];
    }
    private function calculateRemainingDays($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        return $endDate->diffInDays($startDate, true);
    }
    public function applyPromoCode(string $promoCode)
    {
        $promo = PromoCodes::where('code', $promoCode)
            ->where('is_active', 1)
            ->first();
       
        if (!$promo) {
            return ['status' => 'error', 'message' => 'Invalid or expired promo code.'];
        }

        $startDate=$promo->start_date;
        $endDate=$promo->end_date;
        $remainingDays=$this->calculateRemainingDays($startDate, $endDate);  

        if($remainingDays <= 0){
            return ['status' => 'error', 'message' => 'Invalid or expired promo code.'];
        }

        $cart = $this->getCart();
        $cartItems = $cart->items;

        $validProductIds = $promo->products->pluck('id')->toArray();
        $validCartItems = $cartItems->filter(function ($cartItem) use ($validProductIds) {
            return in_array($cartItem->product_id, $validProductIds);
        });

        if ($validCartItems->isEmpty()) {
            return ['status' => 'error', 'message' => 'Promo code does not apply to the products in your cart.'];
        }

        $totalDiscountAmount = 0;
        $updatedCartItems = [];

        foreach ($validCartItems as $cartItem) {
            $finalPrice = $cartItem->price;

            if ($promo->discount_type == 'percentage') {
                $discountAmount = ($cartItem->price * ($promo->discount_amount / 100));
                $finalPrice = $cartItem->price - $discountAmount;
            } else {
                $discountAmount = $promo->discount_amount;
                $finalPrice = max(0, $cartItem->price - $discountAmount);
            }

            $totalDiscountAmount += $discountAmount;

            $cartItem->discount_amount = $finalPrice;  
            //$cartItem->total_after_discount = $finalPrice;
            $updatedCartItems[] = $cartItem;
        }

        
        foreach ($updatedCartItems as $updatedCartItem) {
            $updatedCartItem->save();
        }

        $cart->promo_code = $promoCode;
        $cart->discount_amount = $totalDiscountAmount; 
        $cart->applied_discount = $promo->discount_amount;
        $cart->applied_discount_type = $promo->discount_type;
        $cart->save();

        return [
            'status' => 'success',
            'message' => 'Promo code applied to the products in your cart.',
            'discount_amount' => $totalDiscountAmount, 
        ];
    }

}
