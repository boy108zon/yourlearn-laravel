
@if($userPermissions->contains('edit-category') || $userPermissions->contains('assign-permissions-category') || $userPermissions->contains('remove-category'))
    <div class="dropdown">
        <button class="btn btn-sm btn-bd-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            Choose
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
            
            @if($userPermissions->contains('edit-category'))
                <li><a class="dropdown-item" href="{{ route('categories.edit', $category->id) }}">Edit</a></li>
            @endif

            
            @if($userPermissions->contains('assign-permissions-category'))
             <!--<li><a href="{{ route('roles.permissions', $category->id) }}" class="dropdown-item">Assign Permissions</a></li>-->
            @endif

            @if($userPermissions->contains('remove-category'))
                <li>
                    <form id="delete-form-{{ $category->id }}" action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $category->id }})">Delete</button>
                    </form>
                </li>
            @endif
        </ul>
    </div>
@endif
