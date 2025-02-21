<?php
namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Create Parent Menus
        $parentMenus = [
            [
                'name' => 'Dashboard',
                'title' => 'Dashboard',
                'url' => '/admin/dashboard',
                'slug' => 'dashboard',
                'sequence' => 1,
                'status' => 'active',
                'parent_id' => 0, // Parent ID = 0 for top-level menu
                'icon' => 'bi bi-house-door',
            ],
            [
                'name' => 'Posts',
                'title' => 'Posts',
                'url' => '/admin/posts',
                'slug' => 'posts',
                'sequence' => 2,
                'status' => 'active',
                'parent_id' => 0,
                'icon' => 'bi bi-pencil-square',
            ],
            [
                'name' => 'Users',
                'title' => 'Users',
                'url' => '/admin/users',
                'slug' => 'users',
                'sequence' => 3,
                'status' => 'active',
                'parent_id' => 0,
                'icon' => 'bi bi-person-lines-fill',
            ],
            [
                'name' => 'Settings',
                'title' => 'Settings',
                'url' => '/admin/settings',
                'slug' => 'settings',
                'sequence' => 4,
                'status' => 'active',
                'parent_id' => 0,
                'icon' => 'bi bi-gear',
            ],
            [
                'name' => 'Reports',
                'title' => 'Reports',
                'url' => '/admin/reports',
                'slug' => 'reports',
                'sequence' => 5,
                'status' => 'active',
                'parent_id' => 0,
                'icon' => 'bi bi-file-earmark-bar-graph',
            ],
        ];

        // Insert Parent Menus
        foreach ($parentMenus as $menu) {
            $parentMenu = Menu::create([
                'name' => $menu['name'],
                'title' => $menu['title'],
                'url' => $menu['url'],
                'slug' => $menu['slug'],
                'sequence' => $menu['sequence'],
                'status' => $menu['status'],
                'parent_id' => $menu['parent_id'],
                'icon' => $menu['icon'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Create 3 Child Menus for Each Parent Menu
            $childMenus = [
                [
                    'name' => "{$menu['name']} Child 1",
                    'title' => "{$menu['title']} Child 1",
                    'url' => "{$menu['url']}/child1",
                    'slug' => "{$menu['slug']}-child1",
                    'sequence' => 1,
                    'status' => 'active',
                    'parent_id' => $parentMenu->id, // Parent-child relationship
                    'icon' => 'bi bi-file-earmark',
                ],
                [
                    'name' => "{$menu['name']} Child 2",
                    'title' => "{$menu['title']} Child 2",
                    'url' => "{$menu['url']}/child2",
                    'slug' => "{$menu['slug']}-child2",
                    'sequence' => 2,
                    'status' => 'active',
                    'parent_id' => $parentMenu->id,
                    'icon' => 'bi bi-file-earmark',
                ],
                [
                    'name' => "{$menu['name']} Child 3",
                    'title' => "{$menu['title']} Child 3",
                    'url' => "{$menu['url']}/child3",
                    'slug' => "{$menu['slug']}-child3",
                    'sequence' => 3,
                    'status' => 'active',
                    'parent_id' => $parentMenu->id,
                    'icon' => 'bi bi-file-earmark',
                ],
            ];

            // Insert Child Menus
            foreach ($childMenus as $child) {
                Menu::create([
                    'name' => $child['name'],
                    'title' => $child['title'],
                    'url' => $child['url'],
                    'slug' => $child['slug'],
                    'sequence' => $child['sequence'],
                    'status' => $child['status'],
                    'parent_id' => $child['parent_id'],
                    'icon' => $child['icon'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
