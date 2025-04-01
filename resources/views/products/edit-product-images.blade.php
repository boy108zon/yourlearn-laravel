@extends('layouts.master')

@section('title', 'Edit Product Images')

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
                <form id="product-images-form" action="{{ route('products.storeProductImages', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <div class="card bg-white">
                                <div class="card-body">
                                    <div id="dropzone" class="dropzone"
                                        data-existing-images="{{ json_encode($images) }}"
                                        data-product-id="{{ $product->id }}"
                                        data-product-name="{{ $product->sku }}"
                                        data-store-url="{{ route('products.storeProductImages', ['product' => $product->id]) }}"
                                        data-remove-url="{{ route('products.removeProductImage', ['product' => $product->id]) }}"
                                        data-primary-status-url="{{ route('products.primaryImageStatus', ['product' => $product->id]) }}"
                                        data-csrf-token="{{ csrf_token() }}">
                                        <div class="dz-message">
                                           
                                        </div>
                                    </div>
                                    <small class="text-body-secondary"><strong>Drag images here—JPEG/PNG, 5 MB max. Thumbnails auto-create.</strong></small>
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

@push('styles')
<style>
    
</style>

@endpush

