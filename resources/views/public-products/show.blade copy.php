@extends('layouts.productDetails')

@section('content')
<div class="container-fluid py-5">
    <div class="row">
        <div class="col-12 col-md-7">
            <div class="row g-1">
                @foreach($product->images as $image)
                    @if($image->is_primary)
                        <div class="col-6 mb-3">
                            <div class="product-image-wrapper position-relative">
                                <img src="{{ $productImageService->getImageUrl($image->image_url,'public') }}" 
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

        <div class="col-12 col-md-5">
            <h1 class="fs-3 fs-md-3 fs-lg-4 fw-bold mb-3">{{ $product->name }}</h1>
            <p class="fs-5 fs-md-6 fs-lg-5 text-muted mb-3">{{ $product->description }}</p>
            
            <div class="price-details mt-3">
                <p class="fs-5">
                    @if($HighestPromoCodeDiscount['promo_code'] > 0)
                        <span class="text-muted"><s>MRP ₹{{ number_format($product->price, 2) }}</s></span>
                        <span>Now ₹{{ number_format($product->price - $HighestPromoCodeDiscount['promo_code'], 2) }}</span>
                    @else
                        <span>MRP ₹{{ number_format($product->price, 2) }}</span>
                    @endif
                    <span class="text-success">{{  @($HighestPromoCodeDiscount['promo_code'] > 0) ? ($HighestPromoCodeDiscount['percentage_value']).'% OFF' : NULL }}</span>
                </p>
                <p class="text-primary fs-6">Inclusive of all taxes</p>
            </div>

            <div class="mt-4">
                <form action="{{ route('cart.add', $product->id) }}" method="GET" class="d-inline-block">
                    @csrf
                    <input type="hidden" id="pmcd" name="pmcd" value="{{ $HighestPromoCodeDiscount['promo_code'] }}">
                    <button type="submit" class="btn btn-lg btn-primary w-100 w-md-50 mb-2 mb-md-0 add-to-cart-btn">Add to Cart</button>
                </form>

                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline-block">
                    @csrf
                    <button type="submit" class="btn btn-lg btn-outline-dark w-100 w-md-50 ">Whitelist</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <hr class="my-3">
        <h4 class="my-3">Similar Products</h4>
        <div id="product-list" class="row"></div>
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
                                <img src="{{ $productImageService->getImageUrl($image->image_url, 'public') }}" 
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
                                            class="d-block w-100 img-fluid product-image" 
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

@push('scripts')
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/jquery.ez-plus.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function initializeZoom(selector = '.') {
            const images = document.querySelectorAll(selector);
            images.forEach(image => {
                image.addEventListener('mouseenter', function () {
                    if (!image.dataset.ezPlus) {
                        $(image).ezPlus({
                            responsive : true,
                            scrollZoom : false,
                            showLens: true,
                            zIndex:1080,
                            easing:true,
                            tint: true,
                            tintOpacity: 0.5,
                            borderSize:0
                        });
                        image.dataset.ezPlus = true;
                    }
                });

                image.addEventListener('mouseleave', function () {
                    if (image.dataset.ezPlus) {
                        $(image).ezPlus('destroy');
                        image.dataset.ezPlus = false;
                    }
                });
            });
        }

        $('#imageCarouselModal').on('shown.bs.modal', function () {
            initializeZoom('.product-image');
        });
    });
</script>
@endpush

