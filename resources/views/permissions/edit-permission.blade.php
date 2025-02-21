@extends('layouts.master')

@section('title', 'Create Permission')

@section('content')
<div class="container-fluid">
    <div class="card bg-white">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Permission Name: <b>{{$permission->name}}</b></h5>
        </div>

        <div class="card-body">
            <form action="{{ route('permissions.changename', $permission->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-12">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $permission->name) }}" required >
                                <span class="text-danger">Note: Only name take effects.</span>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                    </div>
                </div>
            

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-bd-primary">Save Changes</button>
                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
