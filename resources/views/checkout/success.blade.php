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
                        <p class="fs-4 mb-3">Thank you for your order, @if(auth()->check()) {{ auth()->user()->name }} @else Guest @endif!</p>
                        <p class="text-muted mb-4">Your order has been received and is being processed. You will be notified once it is shipped.</p>
                        <p class="fs-5 mb-2"><strong>Order Number:</strong> #{{ $order->id }}</p>

                        <p class="fs-5 mb-4"><strong>Total Amount:</strong> ${{ number_format($order->total_price, 2) }}</p>

                        @if($order->discount)
                            <p class="fs-5 mb-4">
                                <strong>Discount:</strong>
                                @if($order->discount_type == 'percentage')
                                    -{{ number_format($order->discount, 2) }}%
                                @else
                                    -${{ number_format($order->discount, 2) }}
                                @endif
                            </p>
                        @endif

                        <p class="fs-5 mb-4"><strong>Final Total:</strong> ${{ number_format($order->total_price - $order->discount, 2) }}</p>
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
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $orderTotal = 0; // Initialize order total before discount
                                    @endphp
                                    @foreach($order->products as $product)
                                        @php
                                            $productTotal = $product->pivot->quantity * $product->price;
                                            $orderTotal += $productTotal; // Add product total to order total
                                        @endphp
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->pivot->quantity }}</td>
                                            <td>${{ number_format($product->price, 2) }}</td>
                                            <td>${{ number_format($productTotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total Before Discount</strong></td>
                                        <td>{{ number_format($orderTotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Discount Applied</strong></td>
                                        <td>{{ number_format($order->discount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total After Discount</strong></td>
                                        <td>{{ number_format($orderTotal - $order->discount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                </div>

                <!-- Action Buttons -->
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
