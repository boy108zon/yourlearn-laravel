@extends('layouts.master')

@section('title', 'Create Permission')

@section('content')
<div class="container-fluid">
    <div class="card bg-white">
        <div class="card-header">
            <h5 class="card-title mb-0">Create New Permission</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('permissions.store') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="name" class="form-label">Permission Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Assign Menu Or Related Module</label>
                            <div class="d-flex flex-wrap">
                                @foreach($menus as $menu)
                                    @if($menu->parent_id == 0) 
                                        <div class="col-6 col-sm-4 col-md-2 col-lg-2 col-xl-2 mb-3">
                                            <input type="radio" name="menus[]" value="{{ $menu->id }}" 
                                                id="menu_{{ $menu->id }}" 
                                                class="form-check-input parent-radio" 
                                                data-child-class="child-menu-{{ $menu->id }}">
                                            <label class="form-check-label" for="menu_{{ $menu->id }}">{{ $menu->name }}</label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            @error('menus')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-bd-primary">Create Permission</button>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
