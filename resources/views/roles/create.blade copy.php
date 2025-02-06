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
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autocomplete="off">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="badge_color" class="form-label">Badge Color</label>
                                <input type="text" id="badge_color" name="badge_color" class="form-control @error('badge_color') is-invalid @enderror" value="{{ old('badge_color') }}" autocomplete="off">
                                @error('badge_color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="menus" class="form-label">Assign Menus</label>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Module</th>
                                        <th>Menu</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table mb-0">
                                    @foreach($menus as $menu)
                                        @if($menu->parent_id == 0) 
                                            @foreach($menus->where('parent_id', $menu->id) as $childMenu)
                                                <tr>
                                                    @if($loop->first)
                                                        <td rowspan="{{ $menus->where('parent_id', $menu->id)->count() }}">
                                                            {{ $menu->name }} 
                                                        </td>
                                                    @endif
                                                    <td>{{ $childMenu->name }}</td> 
                                                    <td>
                                                        <input type="checkbox" 
                                                            class="chkpermission @error('menus') is-invalid @enderror" 
                                                            id="menu{{ $childMenu->id }}" 
                                                            name="menus[]" 
                                                            value="{{ $childMenu->id }}"
                                                            @if(in_array($childMenu->id, $assignedMenus)) checked @endif>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @error('menus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-bd-primary">Create</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
