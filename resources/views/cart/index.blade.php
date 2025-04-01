@extends('layouts.home')

@section('content')
    <div class="container">
        <h4 class="my-4 d-flex justify-content-between align-items-center">
            Your Shopping Cart
            <a href="{{ route('site') }}" class="text-decoration-none d-flex align-items-center">
                Continue Shopping<i class="bi bi-bag fs-4 ms-2" title="View Products"></i> 
            </a>
        </h4>
        
        @if($cartItems->isEmpty())
            <div class="alert alert-info">
                Your cart is empty.
            </div>
            <a href="{{ route('site') }}" class="btn btn-link position-relative">
                <i class="bi bi-cart-fill fs-1"></i> 
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    0
                    <span class="visually-hidden">items in cart</span>
                </span>
            </a>
        @else
            <div class="row">

                <div class="col-md-8">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                                <tr>
                                      <td>
                                        <div class="d-flex align-items-center">
                                            <div class="position-relative me-3">
                                                <img src="{{ asset('storage/'.$item['product_image']) }}" alt="{{ $item['name'] }}" class="cart-img img-fluid" style="width: 200px;height:200px;">
                                                
                                                @if($item['stock_quantity'] <= 0)
                                                    <div class="position-absolute top-50 start-50 translate-middle  badge text-bg-danger text-wrap py-1 px-3 rounded">
                                                        <small><strong>Out of Stock</strong></small>
                                                    </div>
                                                @endif
                                            </div>

                                            <div style="flex: 1;">
                                                <strong>{{ $item['product_name'] }}</strong>
                                                <p class="text-muted">{{ Str::limit($item['product_description'], 50) }}...</p>
                                                <small class="d-block"><strong>SKU:</strong> {{ $item['product_sku'] }}</small>
                                                <small class="d-block"><strong>Weight:</strong> {{ number_format($item['product_weight'], 2) }} kg</small>
                                            </div>
                                        </div>
                                      </td>
                                   
                                    <td>${{ number_format($item['price'], 2) }}</td>
                                    <td>
                                        <form action="{{ route('cart.update', $item['product_id']) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="d-flex align-items-center">
                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control w-auto" style="max-width: 80px;">
                                                <button type="submit" class="btn btn-warning btn-sm ms-2">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                    <td>${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                    <td>
                                    <form action="{{ route('cart.remove', ['cartId' => $item->cart_id, 'productId' => $item->product_id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm ms-2">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="col-md-4">
                  <ul class="list-group mb-3">
                    @php
                        $cartTotal = $cartItems->sum(function($item) {
                            return $item->product->price * $item->quantity;
                        });

                        $totalDiscount = $cartTotal;

                        if ($cartItems->first() && $cartItems->first()->applied_discount) {
                            $discount = $cartItems->first()->applied_discount;
                            if ($cartItems->first()->applied_discount_type == 'percentage') {
                                $totalDiscount = $cartTotal - ($cartTotal * ($discount / 100));  
                            } else {
                                $totalDiscount = $cartTotal - $discount; 
                            }
                        }
                    @endphp

                    @forelse ($cartItems ?? [] as $cartItem)  
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0"><strong>{{ $cartItem->product->name }} </strong></h6>
                                <small class="text-muted">{{ $cartItem->product->description }}</small>
                            </div>
                            <span class="text-muted">${{ number_format($cartItem->product->price, 2) }}</span>
                            
                        </li>
                    @empty
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <span class="text-muted">Your cart is empty</span>
                        </li>
                    @endforelse

                    @if($cartItems && $cartItems->first() && $cartItems->first()->promo_code)
                        <li class="list-group-item d-flex justify-content-between bg-light">
                            <div class="text-success">
                                <h6 class="my-0"><strong>Promo code</strong></h6>
                                <small>{{ $cartItems->first()->promo_code }}</small>
                            </div>
                            <span class="text-success">
                                @if($cartItems->first()->applied_discount_type == 'percentage')
                                    {{ number_format($cartItems->first()->applied_discount, 2) }}%
                                @else
                                    ${{ number_format($cartItems->first()->applied_discount, 2) }}
                                @endif
                            </span>
                        </li>
                    @endif

                    <li class="list-group-item d-flex justify-content-between">
                        <span><strong>Total (USD)</strong></span>
                        <strong>${{ number_format($totalDiscount, 2) }}</strong> 
                    </li>
                 </ul>
                
                 <a href="{{ route('checkout.index') }}" class="btn btn-md btn-bd-primary w-100 mt-3">
                   <i class="bi bi-cart-check"></i> Proceed to Checkout
                </a>
                            
               </div>

            </div>
        @endif
    </div>
@endsection
