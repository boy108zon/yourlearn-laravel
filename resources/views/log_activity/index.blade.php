
@extends('layouts.master')
 
@section('content')
    <div class="container-fluid">
        <div class="card bg-white">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"></h5>
                <div class="row g-3 w-100" id="filter-form" data-module="{{ request()->route()->getName() }}">
                    <div class="col-md-2" data-filter="date">
                        <input type="date" id="start_date" class="form-control" placeholder="Start Date" />
                    </div>
                    <div class="col-md-2" data-filter="date">
                        <input type="date" id="end_date" class="form-control" placeholder="End Date" />
                    </div>
                    <div class="col-md-2" data-filter="select">
                        <select id="user_id" class="form-select">
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-center">
                            <i class="bi bi-funnel fs-3 text-primary" id="applyFilter" style="cursor: pointer;"></i>
                        </div>
                 </div>
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
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush