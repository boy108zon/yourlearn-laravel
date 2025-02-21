<?php
$canEdit = $userPermissions->contains('edit-menu'); 
$canDelete = $userPermissions->contains('remove-menu'); 
$canCreate = $userPermissions->contains('create-menu'); 
$canRestore = $userPermissions->contains('restore-menu'); 
?>

<table class="table table-bordered table-responsive">
    <tbody>
        <tr>
            <td class="col-6">{{ ucfirst($parentMenu->name) }}</td>
            <td class="col-6 text-center">
                @if($canEdit || $canDelete || $canRestore)
                <div class="dropdown">
                    <button class="btn btn-sm btn-bd-primary dropdown-toggle" type="button" id="dropdownMenuButton{{ $parentMenu->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                        Choose
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $parentMenu->id }}">
                        @if($canEdit)
                            <li><a class="dropdown-item" href="{{ route('menus.edit', $parentMenu->id) }}">Edit</a></li>
                        @endif
                        @if($canDelete)
                            <li>
                                <form id="delete-form-{{ $parentMenu->id }}" action="{{ route('menus.destroy', $parentMenu->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $parentMenu->id }})">Delete</button>
                                </form>
                            </li>
                        @endif
                    </ul>
                </div>
                @endif
            </td>
        </tr>

        @foreach($menus as $menu)
            <tr>
                <td class="col-6" style="padding-left: 30px;">{{ ucfirst($menu->name) }}</td>
                <td class="col-6 text-center">
                    @if($canEdit || $canDelete || $canRestore)
                    <div class="dropdown">
                        <button class="btn btn-sm btn-bd-primary dropdown-toggle" type="button" id="dropdownMenuButton{{ $menu->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                            Choose
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $menu->id }}">
                            @if($canEdit)
                                <li><a class="dropdown-item" href="{{ route('menus.edit', $menu->id) }}">Edit</a></li>
                            @endif
                            @if($canDelete)
                                <li>
                                    <form id="delete-form-{{ $menu->id }}" action="{{ route('menus.destroy', $menu->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $menu->id }})">Delete</button>
                                    </form>
                                </li>
                            @endif
                          
                        </ul>
                    </div>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
