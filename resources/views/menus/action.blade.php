
@if($userPermissions->contains('edit-menu') || $userPermissions->contains('create-menu') || $userPermissions->contains('remove-menu'))
    <div class="dropdown">
        <button class="btn btn-sm btn-bd-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            Choose
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
            
            @if($userPermissions->contains('edit-menu'))
                <li><a class="dropdown-item" href="{{ route('menus.edit', $menu->id) }}">Edit</a></li>
            @endif

            
            @if($userPermissions->contains('remove-menu'))
                <li>
                    <form id="delete-form-{{ $menu->id }}" action="{{ route('roles.destroy', $menu->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $menu->id }})">Delete</button>
                    </form>
                </li>
            @endif
        </ul>
    </div>
@endif
