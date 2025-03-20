
@if($userPermissions->contains('edit-promo-code') || $userPermissions->contains('create-promo-code') || $userPermissions->contains('remove-promo-code'))
    <div class="dropdown">
        <button class="btn btn-sm btn-bd-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            Choose
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
        
           
            @if($userPermissions->contains('edit-promo-code'))
                <li><a class="dropdown-item" href="{{ route('promocodes.edit', $promocode->id) }}">Edit</a></li>
            @endif

            
            @if($userPermissions->contains('remove-promo-code'))
                <li>
                    <form id="delete-form-{{ $promocode->id }}" action="{{ route('promocodes.destroy', $promocode->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $promocode->id }})">Delete</button>
                    </form>
                </li>
            @endif
        </ul>
    </div>
@endif
