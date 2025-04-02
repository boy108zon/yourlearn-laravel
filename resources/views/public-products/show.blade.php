@extends('layouts.productDetails')

@section('content')
<div class="container-fluid py-1">
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="row g-1">
                @foreach($product->images as $image)
                    @if($image->is_primary)
                        <div class="col-6 mb-3">
                            <div class="product-image-wrapper position-relative">
                                <img src="{{ $productImageService->getImageUrl($image->image_url) }}" 
                                    alt="{{ $product->name }} - Primary Image"
                                    class="img-fluid rounded shadow-md hover-scale"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#imageCarouselModal">
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="col-12 col-md-4">
            <h1 class="fs-3 fw-bold mb-3">{{ $product->name }}</h1>
            <p class="text-muted mb-3">{{ $product->description }}</p>
            
            <hr class="gradient-hr">
            <div class="price-details mt-3">
                <p class="fs-5">
                   
                    @if($HighestPromoCodeDiscount['promo_code'] > 0)
                        <span class="text-muted"><s> ${{ number_format($product->price, 2) }}</s></span>
                        <span>Now ₹{{ number_format($product->price - $HighestPromoCodeDiscount['discountValue'], 2) }}</span>
                    @else
                        <span> ${{ number_format($product->price, 2) }}</span>
                    @endif
                    <span class="text-success">{{  @($HighestPromoCodeDiscount['discountValue'] > 0) ? ($HighestPromoCodeDiscount['percentage_value']).'% OFF' : NULL }}</span>
                </p>
                <p class="text-primary fs-6">Inclusive of all taxes</p>
            </div>

            @if($product->stock_quantity > 0)
                <p class="text-success">In Stock ({{ $product->stock_quantity }} available)</p>
            @else
                <p class="text-danger">Out of Stock</p>
            @endif

            
            <p><strong>SKU:</strong> {{ $product->sku }}</p>

            @if($product->weight)
                <p>Weight: {{ $product->weight }} kg</p>
            @endif

            <form action="{{ route('cart.add', $product->id) }}" method="GET" class="d-inline-block">
                @csrf
                <input type="hidden" id="pmcd" name="pmcd" value="{{ $HighestPromoCodeDiscount['promo_code'] }}">
                <button type="submit" class="btn btn-primary w-100 mb-2">Add to Cart</button>
            </form>

            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline-block">
                @csrf
                <button type="submit" class="btn btn-outline-dark w-100 mb-2">Whitelist</button>
            </form>

            <x-product-reviews 
                :product="$product" 
                :ratings="$ratings" 
                :averageRating="$averageRating" 
                :ratingCounts="$ratingCounts" 
                :ratingPercentages="$ratingPercentages" 
                :ratingCount="$ratingCount" 
            />

        </div>
    </div>
    
    <div class="modal fade" id="imageCarouselModal" tabindex="-1" aria-labelledby="carouselModalLabel" aria-hidden="true" 
        data-bs-backdrop="false" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content position-relative">
                <button type="button" class="z-index-1 btn-close btn-close-lg position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body p-0 d-flex">
               
                    <div class="thumbnail-container d-flex flex-column justify-content-center align-items-center me-3">
                        @foreach($product->images as $index => $image)
                            <div class="thumbnail-item mb-2">
                                <img src="{{ $productImageService->getImageUrl($image->image_url) }}" 
                                    class="img-fluid rounded-3 thumbnail-preview" 
                                    alt="Thumbnail {{ $index + 1 }}" 
                                    data-bs-target="#carouselExampleControls" 
                                    data-bs-slide-to="{{ $index }}" 
                                    aria-label="Go to image {{ $index + 1 }}">
                            </div>
                        @endforeach
                    </div>
                        
                    <div class="carousel-container w-100">
                        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="false">
                            <div class="carousel-inner">
                                @foreach($product->images as $index => $image)
                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                        <img src="{{ $productImageService->getImageUrl($image->image_url, 'public') }}" 
                                            class="d-block w-100 img-fluid product-image zoom-effects" 
                                            alt="{{ $product->name }} - Image {{ $index + 1 }}" 
                                            id="zoom-image-{{ $index }}" 
                                            data-zoom-image="{{ $productImageService->getImageUrl($image->image_url, 'public') }}"
                                            role="img">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev" aria-label="Previous Image">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next" aria-label="Next Image">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


