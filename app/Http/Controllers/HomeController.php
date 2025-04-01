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
         return view('home.index');
    }
}
