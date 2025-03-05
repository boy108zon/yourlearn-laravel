<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function getCart()
    {
        $showSidebar = false;
        $cartItems = $this->cartService->getCartContents();
        $cartTotal = $this->cartService->getTotalPrice();
        return view('cart.index', compact('cartItems', 'cartTotal', 'showSidebar'));
    }
    
    public function addProductToCart(Request $request, $productId)
    {
        
        $quantity = $request->input('quantity', 1);
        $this->cartService->addProductToCart($productId, $quantity);
        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function removeProductFromCart($productId)
    {
       $removed = $this->cartService->removeCartItem($productId);
       if ($removed) {
           return redirect()->route('cart.index')->with('success', 'Product removed from cart.');
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
