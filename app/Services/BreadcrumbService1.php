<?php
namespace App\Services;

use App\Models\Menu;

class BreadcrumbService
{
    public function generateBreadcrumbs()
    {
        
        $breadcrumbs = [];
        
        $currentUrl = url()->current();
        $parsedUrl = parse_url($currentUrl, PHP_URL_PATH);  
        $currentUrlPath = ltrim($parsedUrl, '/');
        
        $urlSegments = explode('/', $currentUrlPath);

        $menu = Menu::where('url', '/' . $urlSegments[0])->first();

        if ($menu) {
            $breadcrumbs[] = (object) [
                'title' => $menu->title, 
                'url' => $menu->url,
                'icon' => $menu->icon ?? 'bi-folder'
            ];

            while ($menu->parent_id) {
                $menu = $menu->parent;
                $breadcrumbs[] = (object) [
                    'title' => $menu->title,
                    'url' => $menu->url,
                    'icon' => $menu->icon ?? 'bi-folder'
                ];
            }
        } else {
            $breadcrumbs[] = (object) [
                'title' => ucfirst($urlSegments[0]), 
                'name' => ucfirst($urlSegments[0]), 
                'url' => '/' . $urlSegments[0],
                'icon' => 'bi-folder'  // Fallback icon
            ];
        }

        $dynamicBreadcrumbs = [];
        if (count($urlSegments) > 1) {
            foreach (array_slice($urlSegments, 1) as $segment) {
                $menu = Menu::where('url', '/' . $segment)->first();

                if ($menu) {
                    $breadcrumbs[] = (object) [
                        'title' => $menu->title,
                        'url' => $menu->url,
                        'icon' => $menu->icon ?? 'bi-file-earmark'
                    ];
                } else {
                    $dynamicBreadcrumbs[] = (object) [
                        'title' => ucfirst($segment),
                        'name' => ucfirst($segment),
                        'url' => '/' . implode('/', array_slice($urlSegments, 0, array_search($segment, $urlSegments) + 1)),
                        'icon' => $this->getDynamicIcon($segment)  
                    ];
                }
            }
        }

        $breadcrumbs = array_merge($dynamicBreadcrumbs, $breadcrumbs);
        $breadcrumbs = array_reverse($breadcrumbs);
        
        return $breadcrumbs;
    }

    private function getDynamicIcon($segment)
    {
        $icons = [
            'create' => 'bi-plus-circle',  
            'edit'   => 'bi-pencil',       
            'delete' => 'bi-trash',        
        ];

        return $icons[$segment] ?? 'bi-file-earmark';  
    }
}
