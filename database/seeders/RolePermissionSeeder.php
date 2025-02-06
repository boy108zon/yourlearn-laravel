<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Step 1: Create Roles
         $adminRole = Role::create(['name' => 'admin']);
         $userRole = Role::create(['name' => 'user']);
         $managerRole = Role::create(['name' => 'manager']);
         
         // Step 2: Create Permissions
         $viewDashboard = Permission::create(['name' => 'view-dashboard']);
         $createPost = Permission::create(['name' => 'create-post']);
         $editPost = Permission::create(['name' => 'edit-post']);
         
         // Step 3: Assign Permissions to Roles
         // Admin can do everything
         $adminRole->permissions()->attach([$viewDashboard->id, $createPost->id, $editPost->id]);
 
         // User can only view dashboard
         $userRole->permissions()->attach([$viewDashboard->id]);
 
         // Manager can view dashboard and create posts
         $managerRole->permissions()->attach([$viewDashboard->id, $createPost->id]);
 
         // Step 4: Assign Roles to Users
         // Let's create an admin user
         $adminUser = User::create([
             'name' => 'Admin User',
             'email' => 'admin@example.com',
             'password' => bcrypt('password'),
         ]);
         $adminUser->roles()->attach($adminRole); // Assign the admin role to this user
 
         // Let's create a regular user
         $regularUser = User::create([
             'name' => 'Regular User',
             'email' => 'user@example.com',
             'password' => bcrypt('password'),
         ]);
         $regularUser->roles()->attach($userRole); // Assign the user role to this user
 
         // Let's create a manager user
         $managerUser = User::create([
             'name' => 'Manager User',
             'email' => 'manager@example.com',
             'password' => bcrypt('password'),
         ]);
         $managerUser->roles()->attach($managerRole); 
    }
}
