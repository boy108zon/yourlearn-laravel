<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SidebarController extends Controller
{
    public function index()
    {
        // Get the logged-in user
        $user = auth()->user();

        // Get the menus associated with the user's roles
        $menus = $user->menus()->get();

        // Pass menus to the view
        return view('dashboard', compact('menus'));
    }
}
