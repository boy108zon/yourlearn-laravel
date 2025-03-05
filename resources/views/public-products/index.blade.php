@extends('layouts.home')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div id="loader" class="justify-content-center align-items-center position-absolute top-50 start-50 translate-middle" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <h1 class="my-4">Browse Our Products</h1>
                <div id="product-list" class="row">
                   
                </div>
                
                <div class="d-flex justify-content-center">
                    <button id="load-more" class="btn btn-primary" style="display:none;">Load More</button>
                </div>
            </div>
        </div>
    </div>
@endsection
