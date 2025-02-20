@extends('layouts.master')

@section('title', 'Edit Menu')

@section('content')
    <div class="container-fluid">
        <div class="card bg-white">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Menu: <b>{{ $menu->name }}</b></h5>
            </div>

            <div class="card-body">
            <form action="{{ route('menus.update', $menu->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="name" class="form-label">Menu Name <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $menu->name) }}" required autocomplete="off">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="title" class="form-label">Menu Title</label>
                <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $menu->title) }}" autocomplete="off">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="mb-3">
                <label class="form-label">Assign Parent Menu</label>
                <select name="parent_id" id="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                    <option value="0">None (Top Level)</option>
                    @foreach($menus as $parentMenu)
                        <option value="{{ $parentMenu->id }}" {{ old('parent_id', $menu->parent_id) == $parentMenu->id ? 'selected' : '' }}>
                            {{ $parentMenu->name }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Role Assignment -->
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Assign Roles</label>
                <select name="role_ids[]" id="role_ids" class="form-select" multiple>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" 
                                @if(in_array($role->id, old('role_ids', $menu->roles->pluck('id')->toArray()))) selected @endif>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Permission Assignment -->
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Assign Permissions</label>
                <select name="permission_ids[]" id="permission_ids" class="form-select" multiple>
                    @foreach($permissions as $permission)
                        <option value="{{ $permission->id }}" 
                                @if(in_array($permission->id, old('permission_ids', $menu->permissions->pluck('id')->toArray()))) selected @endif>
                            {{ $permission->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <button type="submit" class="btn btn-bd-primary">Save Changes</button>
        <a href="{{ route('menus.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

            </div>
        </div>
    </div>
@endsection
