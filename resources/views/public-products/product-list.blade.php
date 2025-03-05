@foreach($products as $product)
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card shadow-sm border-light">
            <img src="{{ asset('storage/'.$product->image_url) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
            <div class="card-body">
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="card-text text-muted">${{ number_format($product->price, 2) }}</p>
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>
@endforeach
