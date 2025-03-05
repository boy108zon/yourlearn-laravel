@extends('layouts.master')

@section('title', 'Products in ' . $category->name)

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Products in: <strong>{{ $category->name }}</strong></h5>
            </div>
            <div class="card-body">
                {{ $dataTable->table() }} 
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script>
            if (typeof Filters !== 'undefined') {
                Filters.applyFilter('filters');
            }
            $(document).ready(function () { 
                $(document).on('click', '[data-bs-toggle="popover"]', function (e) {
                    $(this).popover({
                        delay: { "show": 500, "hide": 100 },
                        html: true
                    }).popover('show');
                });
           });
        </script>
        <script>
            function confirmDelete(userId) {
                if (confirm('Are you sure you want to delete ?')) {
                    document.getElementById('delete-form-' + userId).submit();
                }
            }
        </script>
        
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    @endpush
  
@endsection


