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

    public function index()
    {
        $showSidebar = false;
        $guestId = session()->getId();
        $cartItems = $this->cartService->getCartContents();
        $cartTotal = $this->cartService->getTotalPrice();

        $user = auth()->user();
        $shippingAddress = $user ? $user->shipping_address : '';
        $billingAddress = $user ? $user->billing_address : '';
        return view('checkout.index', compact('cartItems', 'cartTotal','guestId','shippingAddress', 'billingAddress','showSidebar'));
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


    public function success(Request $request)
    {
        return view('checkout.success');
    }
}
