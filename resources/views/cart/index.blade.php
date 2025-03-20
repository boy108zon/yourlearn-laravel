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
                    <div class="card">
                        <div class="card-header">
                            <strong>Cart Summary</strong>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Subtotal</span>
                                    <span>${{ number_format($cartTotal, 2) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Shipping</span>
                                    <span>Free</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Total</strong>
                                    <strong>${{ number_format($cartTotal, 2) }}</strong>
                                </li>
                            </ul>
                            <a href="{{ route('checkout.index') }}" class="btn btn-sm btn-bd-primary w-100 mt-3">
                                <i class="bi bi-cart-check"></i> Proceed to Checkout
                            </a>
                            
                        </div>
                    </div>
                </div>
            </div>
            
        @endif
    </div>
@endsection
