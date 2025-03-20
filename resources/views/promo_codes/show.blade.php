@extends('layouts.master')

@section('title', 'Show Order')

@section('content')
    <div class="container-fluid">
        <div class="card border-light bg-white">
        <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Show Order: #{{ $order->id }}</h5>
                <button class="btn btn-light mb-0" onclick="printOrder()">
                    <i class="bi bi-printer"></i> Print
                </button>
            </div>

            <div class="card-body" id="order-products-table">
                
                <form>
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label class="form-label">Customer First Name</label>
                                <label class="form-control border-light">{{ old('first_name', $order->first_name) }}</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label class="form-label">Customer Last Name</label>
                                <label class="form-control border-light">{{ old('last_name', $order->last_name) }}</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label class="form-label">Customer Email</label>
                                <label class="form-control border-light">{{ old('email', $order->email) }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Order Status</label>
                                <label class="form-control border-light">
                                    @switch($order->status)
                                        @case('Pending') Pending @break
                                        @case('Processing') Processing @break
                                        @case('Completed') Completed @break
                                        @case('Shipped') Shipped @break
                                        @case('Delivered') Delivered @break
                                        @case('Canceled') Canceled @break
                                        @case('Returned') Returned @break
                                        @case('Refunded') Refunded @break
                                        @default N/A
                                    @endswitch
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Total Price</label>
                                <label class="form-control border-light">{{ $order->total_price }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Shipping Address</label>
                                <label class="form-control border-light">{{ $order->shipping_address }}</label>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Billing Address</label>
                                <label class="form-control border-light">{{ $order->billing_address }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Payment Method <?=$order->payment_method?></label>
                                <label class="form-control border-light">
                                    @switch($order->payment_method)
                                        @case('Paypal') PayPal @break
                                        @case('credit_card') Credit Card @break
                                        @case('debit_card') Debit Card @break
                                        @default N/A
                                    @endswitch
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Tracking Number</label>
                                <label class="form-control border-light">{{ $order->tracking_number ?? 'N/A' }}</label>
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
                                                <th>Total Discount</th>
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
                                                    $productTotal = $orderProduct->pivot->quantity * $orderProduct->price;
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
                                                    <td>{{ $orderProduct->pivot->quantity }}</td>
                                                    <td>{{ $orderProduct->price }}</td>
                                                    <td>
                                                        @if($discountAmount > 0)
                                                            {{ number_format($discountAmount, 2) }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>
                                                       
                                                        @if($discountAmount > 0)
                                                            {{ number_format($discountAmount, 2) }}
                                                        @else
                                                            {{ number_format($productTotal,2) }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-end"><strong>Total Before Discount</strong></td>
                                                <td>{{ number_format($orderTotal,2) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-end"><strong>Final Total</strong></td>
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
                                <label for="cart" class="form-label">Cart Information</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle table-info border-primary">
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
                                                    <td>{{ $order->cart->user_id }} {{ $order->cart->session_id }}</td>
                                                    <td>{{ $order->cart->status }}</td>
                                                    <td>{{ $order->cart->applied_discount_type ?? 'N/A' }}</td>
                                                    <td>{{ $order->cart->applied_discount ?? 'N/A' }}</td>
                                                    <td>{{ $order->cart->promo_code ?? 'N/A' }}</td>
                                                    <td>{{ $order->cart->created_at }}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="7">No cart available for this order.</td>
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
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

