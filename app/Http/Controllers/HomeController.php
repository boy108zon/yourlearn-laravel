<?php

namespace App\Http\Controllers;
use App\Models\Menu;
class HomeController extends Controller
{
    protected $menuService;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    public function index()
    {
        $showSidebar = true;
        $showNavbar = true;

        // Get the current logged-in user
        $user = auth()->user();

        // Get the user's roles (assuming roles are assigned via role_user pivot table)
        $roles = $user->roles;
        
        // Fetch the menus based on the user's roles
        /*$menus = Menu::whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('role_id', $roles->pluck('id')->toArray());
        })->with('children')->get();*/

        
        /*$menus = Menu::whereHas('roles.permissions', function ($query) use ($user) {
            $query->whereIn('permission_id', $user->permissions->pluck('id')->toArray());
        })->get();*/

        
        
        return view('home.index');
    }
}
