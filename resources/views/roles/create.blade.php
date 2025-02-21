@extends('layouts.master')

@section('title', 'Create Role')

@section('content')
    <div class="container-fluid">
        <div class="card bg-white">
            <div class="card-header">
                <h5 class="card-title mb-0">Create New Role</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    @method('PUT') 
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="badge_color" class="form-label">Badge Color</label>
                                <input type="text" id="badge_color" name="badge_color" class="form-control @error('badge_color') is-invalid @enderror" value="{{ old('badge_color') }}" >
                                @error('badge_color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Assign Menus</label>
                                <div class="d-flex flex-wrap">
                                    @foreach($menus as $menu)
                                        @if($menu->parent_id == 0)
                                            <div class="col-6 col-sm-4 col-md-3 mb-3">
                                                <input type="checkbox" name="menus[]" value="{{ $menu->id }}" 
                                                    id="menu_{{ $menu->id }}" 
                                                    class="form-check-input parent-checkbox" 
                                                    data-child-class="child-menu-{{ $menu->id }}" 
                                                    {{ in_array($menu->id, old('menus', $assignedMenus)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="menu_{{ $menu->id }}">{{ $menu->name }}</label>

                                                @foreach($menus as $childMenu)
                                                    @if($childMenu->parent_id == $menu->id) 
                                                        <div class="ms-4 mt-2">
                                                            <input type="checkbox" name="menus[]" value="{{ $childMenu->id }}" 
                                                                id="menu_{{ $childMenu->id }}" 
                                                                class="form-check-input child-checkbox child-menu-{{ $menu->id }}" 
                                                                data-parent-id="menu_{{ $menu->id }}" 
                                                                data-child-class="child-menu-{{ $menu->id }}" 
                                                                {{ in_array($childMenu->id, old('menus', $assignedMenus)) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="menu_{{ $childMenu->id }}">{{ $childMenu->name }}</label>
                                                        </div>
                                                    @endif
                                                @endforeach
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
                        <button type="submit" class="btn btn-bd-primary">Create</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
