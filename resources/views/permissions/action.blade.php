@if($userPermissions->contains('edit-permission') || $userPermissions->contains('remove-permission'))
    <div class="dropdown">
        <button class="btn btn-sm btn-bd-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            Choose
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
            
            @if($userPermissions->contains('edit-permission'))
                <li><a class="dropdown-item" href="{{ route('permissions.edit', $permission->id) }}">Edit</a></li>
            @endif

           
        </ul>
    </div>
@endif
