<?php
namespace App\Services;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class UserActionService
{
    public function getActions($role)
    {
        $actions = [];

        $userPermissions = Auth::user()->availablePermissions()->pluck('slug');
        $canCreateUser = $userPermissions->contains('create-user');
        
        if ($canCreateUser) {
            $actions[] = ['label' => 'Create user', 'route' => route('users.create')];
        }
       
        return $actions;
    }
}
