
@if($userPermissions->contains('edit-product') || $userPermissions->contains('create-order') || $userPermissions->contains('remove-order') || $userPermissions->contains('show-order'))
    <div class="dropdown">
        <button class="btn btn-sm btn-bd-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            Choose
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
        
            @if($userPermissions->contains('show-order'))
               <li><a href="{{ route('orders.show', $order->id) }}" class="dropdown-item">Details</a></li>
            @endif


            @if($userPermissions->contains('edit-product'))
                <li><a class="dropdown-item" href="{{ route('orders.edit', $order->id) }}">Edit</a></li>
            @endif

            
            @if($userPermissions->contains('remove-product'))
                <li>
                    <form id="delete-form-{{ $order->id }}" action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $order->id }})">Delete</button>
                    </form>
                </li>
            @endif
        </ul>
    </div>
@endif
