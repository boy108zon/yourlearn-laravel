@extends('layouts.home')

@section('content')
    <div class="container">
        <h1 class="my-4">Shopping Cart</h1>
        @if($cartItems->isEmpty())
            <div class="alert alert-info">
                Your cart is empty.
            </div>
            <a href="{{ route('site') }}" class="btn btn-primary">Continue Shopping</a>
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
                                        <img src="{{ asset('storage/'.$item['product_image']) }}" alt="{{ $item['name'] }}" class="cart-img" style="width: 200px;height:200px;">
                                        <strong>{{ $item['name'] }}</strong>
                                    </td>
                                    <td>${{ number_format($item['price'], 2) }}</td>
                                    <td>
                                        <form action="{{ route('cart.update', $item['product_id']) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control" style="width: 80px;">
                                            <button type="submit" class="btn btn-warning btn-sm mt-2">Update</button>
                                        </form>
                                    </td>
                                    <td>${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                    <td>
                                        <form action="{{ route('cart.remove', $item['product_id']) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
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
                            <a href="{{ route('checkout.index') }}" class="btn btn-success btn-block mt-3">Proceed to Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('styles')
    <style>
        .cart-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 10px;
        }
    </style>
@endsection
