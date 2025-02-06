@extends('layouts.master')

@section('title', 'Manage Role Permissions')

@section('content')
<div class="container-fluid">
    <div class="card bg-white">
        <div class="card-header">
            <h5 class="card-title mb-0">Assign Permissions to Role: <b>{{ucfirst($role->name)}}</b></h5>
        </div>

        <div class="card-body">
            <form action="{{ route('permissions.updateMenus', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    @foreach($permissions as $permission)
                        <div class="col-6 col-sm-4 col-md-3 mb-3">
                            <input type="checkbox" name="assign_permissions[]" value="{{ $permission->id }}" 
                                id="permission_{{ $permission->id }}" 
                                class="form-check-input"
                                @if(in_array($permission->id, old('assign_permissions', $assignedPermissions ?? []))) checked @endif>
                            <label class="form-check-label" for="permission_{{ $permission->id }}">{{ $permission->name }}</label>
                        </div>
                    @endforeach
                    @error('assign_permissions')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-bd-primary">Save Permission</button>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
