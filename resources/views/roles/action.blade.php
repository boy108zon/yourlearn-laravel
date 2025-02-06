
@if($userPermissions->contains('edit-role') || $userPermissions->contains('assign-permissions') || $userPermissions->contains('remove-role'))
    <div class="dropdown">
        <button class="btn btn-sm btn-bd-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            Choose
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
            
            @if($userPermissions->contains('edit-role'))
                <li><a class="dropdown-item" href="{{ route('roles.edit', $role->id) }}">Edit</a></li>
            @endif

            
            @if($userPermissions->contains('assign-permissions'))
            <li><a href="{{ route('roles.permissions', $role->id) }}" class="dropdown-item">Assign Permissions</a></li>
            @endif

            @if($userPermissions->contains('remove-role'))
                <li>
                    <form id="delete-form-{{ $role->id }}" action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $role->id }})">Delete</button>
                    </form>
                </li>
            @endif
        </ul>
    </div>
@endif
