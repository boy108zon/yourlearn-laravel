<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Menu;
use App\Services\BreadcrumbService;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(BreadcrumbService::class, function ($app) {
            return new BreadcrumbService();
        });
    }

    public function boot(): void
    {
        View::composer('partials.sidebar', function ($view) {
            $user = auth()->user();
            if ($user) {
                $roles = $user->roles()->with('menus')->get();
    
                
                $assignedMenuIds = $roles->flatMap(function ($role) {
                    return $role->menus->pluck('id');
                })->unique();
    
                
                $menus = Menu::with(['children' => function ($query) use ($assignedMenuIds) {
                    $query->whereIn('id', $assignedMenuIds)->where('status', 'active');
                }])
                ->whereIn('id', $assignedMenuIds)
                ->where('parent_id', 0)
                ->where('status', 'active')
                ->orderBy('sequence') 
                ->get();
    
                $menus->each(function ($menu) use ($assignedMenuIds) {
                    $menu->children = $menu->children->filter(function ($child) use ($assignedMenuIds) {
                        return $assignedMenuIds->contains($child->id);
                    })->sortBy('sequence'); 
                });
    
                $currentPath = request()->path();
                $menus->each(function ($menu) use ($currentPath) {
                    if ($this->isMenuActive($menu->url, $currentPath)) {
                        $menu->is_active = true;
                    }
    
                    foreach ($menu->children as $child) {
                        if ($this->isMenuActive($child->url, $currentPath)) {
                            $child->is_active = true;
                            $menu->is_active = true;  
                        }
                    }
                });
    
                $view->with('menus', $menus);
            } else {
                $view->with('menus', collect());
            }
        });
    }
    
     
    private function isMenuActive($menuUrl, $currentPath)
    {
        return strpos($currentPath, trim($menuUrl, '/')) === 0; 
    }
}
