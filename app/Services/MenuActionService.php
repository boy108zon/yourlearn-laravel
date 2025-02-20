<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;

class MenuActionService
{
    public function getActions($user)
    {
        $actions = [];

        $userPermissions = Auth::user()->availablePermissions()->pluck('slug');
        
        $canCreateMenu = $userPermissions->contains('create-menu');
        $canEditMenu = $userPermissions->contains('edit-menu');
        $canDeleteMenu = $userPermissions->contains('remove-menu');
        
        if ($canCreateMenu) {
            $actions[] = ['label' => 'Create New Menu', 'route' => route('menus.create')];
        }

        if ($canEditMenu) {
           // $actions[] = ['label' => 'Edit Menu', 'route' => route('menus.edit', ['menu' => '{menu_id}'])];  // Replace with actual menu logic
        }

        if ($canDeleteMenu) {
            //$actions[] = ['label' => 'Delete Menu', 'route' => route('menus.destroy', ['menu' => '{menu_id}'])];  // Replace with actual menu logic
        }

        
        return $actions;
    }
}
