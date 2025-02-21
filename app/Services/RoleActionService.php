<?php
namespace App\Services;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class RoleActionService
{
    public function getActions($role)
    {
        $actions = [];

        $userPermissions = Auth::user()->availablePermissions()->pluck('slug');

        $canCreateRole = $userPermissions->contains('create-role');
        $canCreatePermisson = $userPermissions->contains('create-permissions');
        $canlistPermisson = $userPermissions->contains('list-permission');

        if ($canCreateRole) {
            $actions[] = ['label' => 'Create New role', 'route' => route('roles.create')];
        }
 
        if ($canCreatePermisson) {
            $actions[] = ['label' => 'Create New Permission', 'route' => route('permissions.create')];
        }
        
        if ($canlistPermisson) {
            $actions[] = ['label' => 'List Permissions', 'route' => route('permissions.index')];
        }
        
        return $actions;
    }
}
