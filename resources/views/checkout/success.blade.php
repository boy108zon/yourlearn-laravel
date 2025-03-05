
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Order Confirmed</h1>

        <p>Thank you for your purchase!</p>
        <p>Your order has been successfully placed. We will send you an email with the details shortly.</p>

        <a href="{{ route('site') }}" class="btn btn-primary">Back to Home</a>
    </div>
@endsection
