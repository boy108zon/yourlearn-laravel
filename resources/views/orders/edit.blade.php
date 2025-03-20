@extends('layouts.master')

@section('title', 'Edit Order')

@section('content')
    <div class="container-fluid">
        <div class="card bg-white">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Order: #{{ $order->id }}</h5>
            </div>

            <div class="card-body" id="order-products-table">
                <form action="{{ route('orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">Customer First Name <span class="text-danger">*</span></label>
                                <input type="text" id="first_name" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $order->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Customer Last Name <span class="text-danger">*</span></label>
                                <input type="text" id="last_name" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $order->last_name) }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label for="email" class="form-label">Customer Email <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $order->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                        <div class="mb-3">
                          <label for="status" class="form-label">Order Status <span class="text-danger">*</span></label>
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ old('status', $order->status) == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="shipped" {{ old('status', $order->status) == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ old('status', $order->status) == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="canceled" {{ old('status', $order->status) == 'canceled' ? 'selected' : '' }}>Canceled</option>
                                <option value="returned" {{ old('status', $order->status) == 'returned' ? 'selected' : '' }}>Returned</option>
                                <option value="refunded" {{ old('status', $order->status) == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        </div>

                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="total_price" class="form-label">Total Price <span class="text-danger">*</span></label>
                                <input type="number" id="total_price" name="total_price" class="form-control @error('total_price') is-invalid @enderror" value="{{ old('total_price', $order->total_price) }}" step="0.01" required>
                                @error('total_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="shipping_address" class="form-label">Shipping Address <span class="text-danger">*</span></label>
                                <textarea id="shipping_address" name="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" required>{{ old('shipping_address', $order->shipping_address) }}</textarea>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="billing_address" class="form-label">Billing Address <span class="text-danger">*</span></label>
                                <textarea id="billing_address" name="billing_address" class="form-control @error('billing_address') is-invalid @enderror" required>{{ old('billing_address', $order->billing_address) }}</textarea>
                                @error('billing_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select id="payment_method" name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                    <option value="paypal" {{ old('payment_method', $order->payment_method) == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                    <option value="credit_card" {{ old('payment_method', $order->payment_method) == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="bank_transfer" {{ old('payment_method', $order->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="tracking_number" class="form-label">Tracking Number</label>
                                <input type="text" id="tracking_number" name="tracking_number" class="form-control @error('tracking_number') is-invalid @enderror" value="{{ old('tracking_number', $order->tracking_number) }}">
                                @error('tracking_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="products" class="form-label">Products <span class="text-danger">*</span></label>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle table-info border-primary">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $orderTotal = 0; 
                                                $totalDiscount = 0;
                                                $discountTotal=0;
                                            @endphp
                                            @foreach($order->products as $orderProduct)
                                                @php
                                                    $productTotal = number_format($orderProduct->pivot->quantity * $orderProduct->price,2);
                                                    $orderTotal += $productTotal;
                                                    $discountAmount = $orderProduct->pivot->discount_amount;

                                                    $totalDiscount += $discountAmount;
                                                    if($discountAmount > 0){
                                                       $discountTotal += $discountAmount;
                                                    }else{
                                                       $discountTotal += $productTotal;
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>{{ $orderProduct->name }}</td>
                                                    <td>
                                                        <input type="number" name="order_products[{{ $orderProduct->id }}][quantity]" value="{{ $orderProduct->pivot->quantity }}" class="form-control">
                                                    </td>
                                                    <td>{{ number_format($orderProduct->price,2) }}</td>
                                                    <td>
                                                       @if($discountAmount > 0)
                                                            {{$discountAmount}}
                                                        @else
                                                           {{ $productTotal }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>Total Before Discount</strong></td>
                                                <td>{{ number_format($orderTotal,2) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>Final Total</strong></td>
                                                @php
                                                   $finalPrice = $discountTotal;
                                                @endphp
                                                <td>{{ number_format($finalPrice,2) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="cart" class="form-label">Cart Information </label>
                                <div class="table-responsive">
                                    <table  class="table table-bordered align-middle table-info border-primary">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Status</th>
                                                <th>Applied Type</th>
                                                <th>Applied Discount</th>
                                                <th>Promo Code</th>
                                                <th>Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($order->cart)
                                                <tr>
                                                    <td>{{ $order->cart->user_id }} {{ $order->cart->session_id }} </td>
                                                    <td>{{ $order->cart->status }}</td>
                                                    <td>{{ $order->cart->applied_discount_type ?? 'N/A' }}</td>
                                                    <td>{{ $order->cart->applied_discount ?? 'N/A' }}</td>
                                                    
                                                    <td>{{ $order->cart->promo_code ?? 'N/A' }}</td>
                                                    <td>{{ $order->cart->created_at }}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="6">No cart available for this order.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                @error('cart')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-bd-primary">Save Changes</button>
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
