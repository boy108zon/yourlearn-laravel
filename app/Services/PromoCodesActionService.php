<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;

class PromoCodesActionService
{
    public function getActions($user)
    {
        $actions = [];

        $userPermissions = Auth::user()->availablePermissions()->pluck('slug');
        
        $canCreateMenu = $userPermissions->contains('create-promo-code');
        $canEditMenu = $userPermissions->contains('edit-promo-code');
        $canDeleteMenu = $userPermissions->contains('remove-promo-code');
        
        if ($canCreateMenu) {
            $actions[] = ['label' => 'Create new', 'route' => route('promocodes.create')];
        }
        
        return $actions;
    }
}
