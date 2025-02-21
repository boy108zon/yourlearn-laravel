@extends('layouts.master')

@section('title', 'Assign Permissions to Role')

@section('content')
    <div class="container-fluid">
        <div class="card bg-white">
            <div class="card-header">
                <h5 class="card-title mb-0">Assign Permissions to Role: {{ $role->name }}</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('roles.permissions.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="permissions" class="form-label">Assign Permissions</label>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Permission</th>
                                        <th>Associated Menus</th> <!-- Added Menus column -->
                                        <th>Assign</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permissions as $permission)
                                        <tr>
                                            <td>{{ $permission->name }}</td>
                                            <td>
                                                <!-- Display associated menus -->
                                                @foreach($permission->menus as $menu)
                                                    {{ $menu->name }}
                                                    @if (!$loop->last), @endif <!-- Add comma if there are multiple menus -->
                                                @endforeach
                                            </td>
                                            <td>
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                                    {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Assign Permissions</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
