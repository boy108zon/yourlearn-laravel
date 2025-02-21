<?php
namespace App\Services;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class PermissionActionService
{
    public function getActions($role)
    {
        $actions = [];

        $userPermissions = Auth::user()->availablePermissions()->pluck('slug');
        $canCreatePermisson = $userPermissions->contains('create-permissions');
       
        if ($canCreatePermisson) {
            $actions[] = ['label' => 'Create New Permission', 'route' => route('permissions.create')];
        }
        
       
        
        return $actions;
    }
}
