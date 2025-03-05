<?php

namespace App\Http\Controllers;
use App\Models\Menu;
class HomeController extends Controller
{
    protected $menuService;
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
         $user = auth()->user();
         $roles = $user->roles;
         return view('home.index');
    }
}
