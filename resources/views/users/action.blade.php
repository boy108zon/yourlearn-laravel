@if($userPermissions->contains('edit-user') || $userPermissions->contains('reset-password-for-users') || $userPermissions->contains('remove-user'))
    <?php
        $role = $user->roles->first();
        $roleId = $role ? $role->id : null; 
    ?>

    <div class="dropdown">
        <button class="btn btn-sm btn-bd-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            Choose
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
            
            @if($userPermissions->contains('edit-user'))
                <li><a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">Edit</a></li>
            @endif

            
            @if($userPermissions->contains('reset-password-for-users'))
                <li><a class="dropdown-item" href="{{ route('users.resetPassword', $user->id) }}">Reset Password</a></li>
            @endif

            @if($userPermissions->contains('remove-user') && $roleId !== 1)
                <li>
                    <form ram="{{$role = $user->roles->first()}}" id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $user->id }})">Delete</button>
                    </form>
                </li>
            @endif
        </ul>
    </div>
@endif
