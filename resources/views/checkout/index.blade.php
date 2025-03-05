@extends('layouts.home')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2>Checkout Form</h2>
        <p class="lead">Complete your purchase by filling out the form below. All required fields must be completed for successful checkout.</p>
    </div>

    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Your cart</span>
                <span class="badge bg-secondary rounded-pill">{{ count($cartItems) }}</span>
            </h4>
            <ul class="list-group mb-3">
                @foreach ($cartItems as $cartItem)
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">{{ $cartItem->product->name }}</h6>
                            <small class="text-muted">{{ $cartItem->product->description }}</small>
                        </div>
                        <span class="text-muted">${{ $cartItem->price }}</span>
                    </li>
                @endforeach
                <li class="list-group-item d-flex justify-content-between bg-light">
                    <div class="text-success">
                        <h6 class="my-0">Promo code</h6>
                        <small>EXAMPLECODE</small>
                    </div>
                    <span class="text-success">-$5</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total (USD)</span>
                    <strong>${{ number_format($cartTotal, 2) }}</strong>
                </li>
            </ul>

            <form class="card p-2">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Promo code">
                    <button type="submit" class="btn btn-secondary">Redeem</button>
                </div>
            </form>
        </div>

        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">Billing Address</h4>
            <form method="POST" action="{{ route('checkout.process') }}" class="needs-validation" novalidate>
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" required 
                               value="{{ auth()->check() ? auth()->user()->first_name : old('first_name') }}">
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" required 
                               value="{{ auth()->check() ? auth()->user()->last_name : old('last_name') }}">
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="you@email.com" 
                           value="{{ auth()->check() ? auth()->user()->email : old('email') }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="shipping_address">Shipping Address</label>
                    <textarea class="form-control @error('shipping_address') is-invalid @enderror" id="shipping_address" name="shipping_address" placeholder="1234 Main St" required>{{ old('shipping_address') }}</textarea>
                    @error('shipping_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="billing_address">Billing Address</label>
                    <textarea class="form-control @error('billing_address') is-invalid @enderror" id="billing_address" name="billing_address" placeholder="Apartment or suite">{{ old('billing_address') }}</textarea>
                    @error('billing_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label for="country">Country</label>
                        <select class="form-select @error('country') is-invalid @enderror" id="country" name="country" required>
                            <option value="">Choose...</option>
                            <option>United States</option>
                        </select>
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="state">State</label>
                        <select class="form-select @error('state') is-invalid @enderror" id="state" name="state" required>
                            <option value="">Choose...</option>
                            <option>California</option>
                        </select>
                        @error('state')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="zip">Zip</label>
                        <input type="text" class="form-control @error('zip') is-invalid @enderror" id="zip" name="zip" required value="{{ old('zip') }}">
                        @error('zip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="mb-4">

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="same-address" name="same_address">
                    <label class="form-check-label" for="same-address">Billing Address is the same as my Shipping address</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="save-info" name="save_info" value="1">
                    <label class="form-check-label" for="save-info">Save this information for next time</label>
                </div>

                <hr class="mb-4">

                <h4 class="mb-3">Payment</h4>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" id="credit" value="credit_card" checked required>
                    <label class="form-check-label" for="credit">Credit Card</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" id="debit" value="debit_card" required>
                    <label class="form-check-label" for="debit">Debit Card</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal" required>
                    <label class="form-check-label" for="paypal">PayPal</label>
                </div>

                <hr class="mb-4">
                <button class="btn btn-primary btn-lg btn-block" type="submit">Continue to checkout</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('same-address').addEventListener('change', function() {
        const shippingAddress = document.getElementById('shipping_address').value;
        const billingAddress = document.getElementById('billing_address');
        if (this.checked) {
            billingAddress.value = shippingAddress;
        } else {
           
            billingAddress.disabled = false;
        }
    });
</script>    
@endpush
