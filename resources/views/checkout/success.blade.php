@extends('layouts.master')

@section('title', 'Order Successful')

@section('content')
    <div class="container-fluid my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12">

                <div class="card shadow-sm mb-4" id="success-message">
                    <div class="card-header text-center  text-dark">
                        <h4><i class="bi bi-check-circle"></i> Order Successful!</h4>
                    </div>
                    <div class="card-body text-center">
                        <p class="fs-5 mb-3">Thank you for your order, @if(auth()->check()) {{ auth()->user()->name }} @else Guest @endif!</p>
                        <p class="text-muted mb-4">Your order has been received and is being processed. You will be notified once it is shipped.</p>
                        <p class="fs-5 mb-2"><strong>Order Number:</strong> #{{ $order->id }}</p>
                        <p class="fs-5 mb-2"><strong>Tracking Number:</strong> {{ $order->tracking_number }}</p>
                         
                        @if($order->cart)
                            <p class="fs-5 mb-4">
                                <strong>Promo Code: </strong> {{$order->cart->promo_code}},
                                <strong> Discount:</strong>
                                @if($order->cart->applied_discount_type == 'percentage')
                                    {{ $order->cart->applied_discount}}%
                                @else
                                    ${{ number_format($order->cart->applied_discount, 2) }}
                                @endif
                                
                            </p>
                        @endif

                        <p class="fs-5 mb-4"><strong>Final Paid Total:</strong> ${{ number_format($order->total_price - $order->discount, 2) }}</p>
                    </div>
                </div>

                <div id="order-details">
                      <h5><i class="bi bi-list"></i> Order Details</h5>
                  
                       <div class="table-responsive">
                            <table class="table table-bordered align-middle  border-primary">
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

                <div class="d-flex justify-content-between mt-4">
                    <button class="btn btn-outline-primary" onclick="printOrderDetails()">
                        <i class="bi bi-printer"></i> Print
                    </button>
                    <a href="{{ route('site') }}" class="text-decoration-none d-flex align-items-center">
                        <i class="bi bi-bag fs-4 me-2"></i> Continue Shopping
                    </a>
                </div>

            </div>
        </div>
    </div>

@push('styles')
<style>
    @media print {
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container-fluid {
            width: 100%;
            padding: 20px;
        }

        .card-header {
            background-color: #f8f9fa;
            color: #333;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        .btn, .d-flex {
            display: none; 
        }

        .card-body {
            padding: 10px;
        }
    }
</style>
@endpush

<script>
    function printOrderDetails() {
        var printContents = document.getElementById('success-message').innerHTML + document.getElementById('order-details').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>

@endsection
