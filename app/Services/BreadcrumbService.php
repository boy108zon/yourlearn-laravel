<?php
namespace App\Services;

use App\Models\Menu;

class BreadcrumbService
{
    public function generateBreadcrumbs(){

        $breadcrumbs = [];
        $currentUrl = url()->current();
        $parsedUrl = parse_url($currentUrl, PHP_URL_PATH);  
        $currentUrlPath = ltrim($parsedUrl, '/');
        $urlSegments = explode('/', $currentUrlPath);

        $menu = Menu::where('url', '/' . $urlSegments[0])->first();

        if ($menu) {
            $staticBreadcrumbs = [];
            while ($menu) {
                $staticBreadcrumbs[] = (object) [
                    'title' => $menu->title,
                    'url' => $menu->url,
                    'icon' => $menu->icon ?? 'bi-folder'
                ];
                $menu = $menu->parent;
            }

            $staticBreadcrumbs = array_reverse($staticBreadcrumbs);
            $breadcrumbs = array_merge($breadcrumbs, $staticBreadcrumbs);
        }

        $dynamicBreadcrumbs = [];
        if (count($urlSegments) > 1) {
            foreach (array_slice($urlSegments, 1) as $segment) {
                
                if (is_numeric($segment)) {
                    continue; 
                }

                $menu = Menu::where('url', '/' . $segment)->first();

                if ($menu) {
                    $dynamicBreadcrumbs[] = (object) [
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

        $breadcrumbs = array_merge($breadcrumbs, $dynamicBreadcrumbs);
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
