<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService; 
use App\Models\Order; 
use App\Models\OrderProduct;
use app\Models\User;
use App\Http\Requests\CreateCheckoutRequest;
use Illuminate\Support\Facades\Auth;
class CheckoutController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(){

        $showSidebar = false;
        $guestId = session()->getId();
        
        $cartItems = $this->cartService->getCartContents();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('site')->with('custom_alert_type', 'info')->with('custom_alert_message', 'Your cart is empty.');
        }
    
        $cartTotal = $this->cartService->getTotalPrice();
        $totalDiscount = $cartItems->sum(function ($cartItem) {
            return $cartItem->discount_amount * $cartItem->quantity;
        });
    
        $user = auth()->user();
        $shippingAddress = $user ? $user->shipping_address : '';
        $billingAddress = $user ? $user->billing_address : '';
   
        return view('checkout.index', compact('cartItems', 'totalDiscount', 'cartTotal', 'guestId', 'shippingAddress', 'billingAddress', 'showSidebar'));
    }
    
    
    public function processCheckout(CreateCheckoutRequest $request)
    {
        $validatedData = $request->validated();
        $response = $this->cartService->processCheckout($validatedData);

        if ($response['status'] === 'error') {
            session()->flash('error', $response['message']);
            return redirect()->route('checkout.index')->with('custom_alert_type', 'info')->with('custom_alert_message', $response['message']);
        }

        return redirect()->route('checkout.success',['order' => $response['order']])->with('custom_alert_type', 'success')->with('custom_alert_message', 'Your order has been placed.');
    }

    public function applyPromoCode(Request $request)
    {
        
        $request->validate([
            'promo_code' => 'required|string|max:255'
        ]);

        $promoCode = $request->input('promo_code');
        $response = $this->cartService->applyPromoCode($promoCode);
        
        if ($response['status'] === 'error') {
            return redirect()->route('checkout.index')->with('swal', [
                'message' => $response['message'],
                'type' => 'error',
            ]);
        }

        return redirect()->route('checkout.index')->with('swal', [
            'message' => $response['message'],
            'type' => 'success',
        ]);
    }

    public function success(Request $request){

        $showSidebar = false;
        $order = Order::with('products')->where('id', session('order_id'))->first();
        if (!$order) {
            return redirect()->route('site')->with('error', 'Order not found.');
        }

        if (session()->has('order_id')) {
            session()->forget('order_id');
        }
        return view('checkout.success', compact('order','showSidebar'));
    }
}
