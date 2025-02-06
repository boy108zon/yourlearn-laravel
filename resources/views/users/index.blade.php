@extends('layouts.master')
 
@section('content')
    <div class="container-fluid">
        <div class="card bg-white">
          <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"></h5>
                <div class="row g-3 w-100" id="filter-form">
                    <div class="col-md-2" data-filter="date">
                        <input type="date" id="start_date" class="form-control" placeholder="Start Date" />
                    </div>
                    <div class="col-md-2" data-filter="date">
                        <input type="date" id="end_date" class="form-control" placeholder="End Date" />
                    </div>

                <div class="col-md-2 d-flex align-items-center">
                        <i class="bi bi-funnel fs-3 text-primary" id="applyFilter" style="cursor: pointer;"></i>
                    </div>
                </div>

                
                @include('users.manage-action')
                
            </div>

            <div class="card-body">
                {{ $dataTable->table() }} 
            </div>
        </div>
    </div>
    
@endsection

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
    </script>
    <script>
        function confirmDelete(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                document.getElementById('delete-form-' + userId).submit();
            }
        }
    </script>
    
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush