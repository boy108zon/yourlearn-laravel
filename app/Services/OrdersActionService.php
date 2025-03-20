<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;

class OrdersActionService
{
    public function getActions($user)
    {
        $actions = [];

        $userPermissions = Auth::user()->availablePermissions()->pluck('slug');
        
        $canCreateMenu = $userPermissions->contains('create-product');
        $canEditMenu = $userPermissions->contains('edit-product');
        $canDeleteMenu = $userPermissions->contains('remove-product');
        
        if ($canCreateMenu) {
            $actions[] = ['label' => 'Create new', 'route' => route('products.create')];
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
