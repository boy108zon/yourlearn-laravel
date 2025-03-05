<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function getCart()
    {
        if (Auth::check()) {
            return Cart::with('items')->firstOrCreate(['user_id' => Auth::id()]);
        } else {
            $sessionId = session()->getId();
            return Cart::with('items')->firstOrCreate(['session_id' => $sessionId]);
        }
    }
    
    public function addProductToCart(int $productId, int $quantity): bool
    {
        $product = Product::find($productId);
        if (!$product) {
            return false;  
        }
        
        if ($quantity <= 0 || $product->stock_quantity < $quantity) {
            return false;
        }

        $cart = $this->getCart();
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

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
    }

    public function removeCartItem(int $productId): bool
    {
        $cart = $this->getCart();
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->delete();
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

        return $total ?: 0; 
    }

    
    public function getCartContents()
    {
        $cart = $this->getCart();
        $cartItems = $cart->items()->with('product')->get();

        return $cartItems->map(function ($cartItem) {
            if ($cartItem->product) {
                $cartItem->product_image = $cartItem->product->image_url;
                $cartItem->product_name = $cartItem->product->name;  
                $cartItem->product_price = $cartItem->product->price; 
                $cartItem->product_description = $cartItem->product->description; 
            } else {
                $cartItem->product_image = null;
                $cartItem->product_name = null;
                $cartItem->product_price = null;
                $cartItem->product_description = null;
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
    
            $paymentMethod = $checkoutData['payment_method'] ?? null;
            $shippingAddress = $checkoutData['shipping_address'] ?? null;
            $billingAddress = $checkoutData['billing_address'] ?? null;
            $country = $checkoutData['country'] ?? null;
            $state = $checkoutData['state'] ?? null;
            $zip = $checkoutData['zip'] ?? null;
    
            if (!$paymentMethod || !$shippingAddress || !$billingAddress || !$country || !$state || !$zip) {
                return ['status' => 'error', 'message' => 'All shipping, billing details, and payment method are required.'];
            }
    
            $totalPrice = $this->getTotalPrice();
    
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
                'shipping_address' => $shippingAddress,
                'billing_address' => $billingAddress,
                'payment_method' => $paymentMethod,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);
    
            $order->tracking_number = $this->generateTrackingNumber($order->id);
            $order->save();
            
            session(['order_id' => $order->id]);
            
            foreach ($cart->items as $cartItem) {
                $order->products()->attach(
                    $cartItem->product_id,
                    ['quantity' => $cartItem->quantity, 'price' => $cartItem->price]
                );
            }
    
            $cart->setStatusCompleted();
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
}
