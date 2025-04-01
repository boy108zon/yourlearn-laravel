<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Models\PromoCodes;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function getCart(Request $request)
    {
        $auto_promo_code = $request->input('pmcd', 0);
        $showSidebar = false;
        $cartItems = $this->cartService->getCartContents();
       
        $cartTotal = $cartItems->sum(function ($cartItem) {
            return $cartItem->quantity * $cartItem->product->price;
        });
       
        return view('cart.index', compact('cartItems', 'cartTotal', 'showSidebar','auto_promo_code'));
    }
    
    public function addProductToCart(Request $request, $productId)
    {
        
        $auto_promo_code = $request->input('pmcd', 0);
        $quantity = $request->input('quantity', 1);
        $result=$this->cartService->addProductToCart($productId, $quantity,$auto_promo_code);
        
        if(!$result){
            return redirect()->route('cart.index')->with('swal', [
                'message' => 'Product could not be added to the cart (out of stock or invalid quantity).',
                'type' => 'error',
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Product added to cart!');

    }

    public function removeProductFromCart($cartId, $productId)
    {
       $removed = $this->cartService->removeCartItem($cartId,$productId);
       if ($removed) {

        return redirect()->route('cart.index')->with('swal', [
            'message' => 'Product removed from cart.',
            'type' => 'success',
        ]);
        
       }

       return redirect()->route('cart.index')->with('error', 'Product not found in the cart.');
    }

    public function clearCart()
    {
      
        $this->cartService->clearCart();
        return redirect()->route('cart.index')->with('success', 'Cart has been cleared!');
    }

    public function updateCart(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = $request->input('quantity');
        $updated = $this->cartService->updateCartItem($productId, $quantity);

        if ($updated) {
            return redirect()->route('cart.index')->with('swal', [
                'message' => 'Cart updated successfully!',
                'type' => 'success',
            ]);
        }

        return redirect()->route('cart.index')->with('swal', [
            'message' => 'Unable to update cart.!',
            'type' => 'danger',
        ]);
    }

}
