
@if($userPermissions->contains('edit-product') || $userPermissions->contains('create-product') || $userPermissions->contains('remove-product'))
    <div class="dropdown">
        <button class="btn btn-sm btn-bd-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            Choose
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
            
            @if($userPermissions->contains('edit-product'))
                <li><a class="dropdown-item" href="{{ route('products.edit', $product->id) }}">Edit</a></li>
            @endif

            
            @if($userPermissions->contains('assign-permissions-product'))
             <!--<li><a href="{{ route('roles.permissions', $category->id) }}" class="dropdown-item">Assign Permissions</a></li>-->
            @endif

            @if($userPermissions->contains('remove-product'))
                <li>
                    <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $product->id }})">Delete</button>
                    </form>
                </li>
            @endif
        </ul>
    </div>
@endif
