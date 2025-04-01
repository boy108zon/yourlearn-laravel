@extends('layouts.master')

@section('title', 'Edit Product thumbnails')

@section('content')
    <div class="container-fluid">
         <div id="dropzone-error-messages" class="alert  d-none"></div>
    </div>
    <div class="container-fluid">
       <div class="card bg-white">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Product Thumbnails: {{ $product->name }}</h5>
            </div>

            <div class="card-body">
                <small>select product image to which you want to upload Thumbnail</small>
                <form action="{{ route('products.editproductThumnails', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <div class="card bg-white">
                                <div class="card-body">
                                    <div id="dropzone" class="dropzone"
                                        data-existing-images="{{ json_encode($productThumbnails) }}"
                                        data-product-id="{{ $product->id }}"
                                        data-product-name="{{ $product->sku }}"
                                        data-store-url="{{ route('products.storeProductThumnails', ['product' => $product->id]) }}"
                                        data-remove-url="{{ route('products.removeProductThumnails', ['product' => $product->id]) }}"
                                        data-primary-status-url="{{ route('products.primaryThumnailsStatus', ['product' => $product->id]) }}"
                                        data-csrf-token="{{ csrf_token() }}">
                                        <div class="dz-message">
                                            <p>Drag images here to upload</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-12">
                        <div id="existing-images-container" class="row">
                        </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function selectThumbnail(id, imageUrl) {
    const productImageInput = document.getElementById('productImage');
    if (productImageInput) { // Check if the element exists
        productImageInput.value = id;
    } else {
        console.error("Input element with ID 'productImage' not found.");
    }

    const thumbnailPreview = document.getElementById('thumbnailPreview');
    if (thumbnailPreview) {
        thumbnailPreview.src = imageUrl;
        thumbnailPreview.style.display = 'block';
    }
}

</script>

@endpush

