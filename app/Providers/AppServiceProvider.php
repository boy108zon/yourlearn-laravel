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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void 
    {
        View::composer('partials.sidebar', function ($view) {
            $user = auth()->user();
            if ($user) {
                $roles = $user->roles;

                $assignedMenuIds = $roles->flatMap(function ($role) {
                    return $role->menus->pluck('id'); 
                })->unique();

                
                $menus = Menu::with(['children' => function ($query) {
                    $query->where('status', 'active');
                }])
                ->whereIn('id', $assignedMenuIds)
                ->where('parent_id', 0)
                ->where('status', 'active')
                ->get();

                $menus = $menus->sortBy('sequence');

                $menus->each(function ($menu) {
                    $menu->children = $menu->children->sortBy('sequence'); 
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
