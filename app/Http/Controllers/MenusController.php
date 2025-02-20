<?php

namespace App\Http\Controllers;

use App\DataTables\MenusDataTable;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use App\Http\Requests\CreateMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Services\MenuActionService;
use Illuminate\Support\Str;
class MenusController extends Controller
{
    protected $menuActionService;

    public function __construct(MenuActionService $menuActionService)
    {
        $this->middleware('auth');
        $this->menuActionService = $menuActionService;
    }

    public function index(MenusDataTable $dataTable)
    {
        $user = auth()->user();
        $actions = $this->menuActionService->getActions($user);
        return $dataTable->render('menus.index', compact('actions'));
    }

    public function create()
    {
       $menus = Menu::all(); 
       return view('menus.create', compact('menus'));
    }

    public function store(CreateMenuRequest $request)
    {
        $validated = $request->validated();
    
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
    
        Menu::create($validated);
    
        return redirect()->route('menus.index')->with('swal', [
            'message' => 'Menu created successfully!',
            'type' => 'success',
        ]);
    }
    

    public function edit(Menu $menu)
    {
        $menus = Menu::where('id', '!=', $menu->id)->get();
        return view('menus.edit', compact('menu','menus'));
    }
    
    public function editARolePermission(Menu $menu)
    {
        $menus = Menu::where('id', '!=', $menu->id)->get();
        $roles = Role::all(); 
        $permissions = Permission::all(); 
        return view('menus.edit', compact('menu', 'menus', 'roles', 'permissions'));
    }

    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $validated = $request->validated();
        $menu->update($validated);

        /*if ($request->has('role_ids')) {
            $menu->roles()->sync($request->input('role_ids'));
        }

        if ($request->has('permission_ids')) {
            $menu->permissions()->sync($request->input('permission_ids'));
        }*/

        return redirect()->route('menus.index')->with('swal', [
            'message' => 'Menu updated successfully!',
            'type' => 'success',
        ]);
    }

    public function destroy(Menu $menu){

        $menu->roles()->detach();
        $menu->permissions()->detach();

        $menu->forceDelete();

        return redirect()->route('menus.index')->with('swal', [
            'message' => 'Menu deleted permanently!',
            'type' => 'success'
        ]);
   }

    public function destroySoft(Menu $menu)
    {
        
        $menu->roles()->detach();
        $menu->permissions()->detach();
        $menu->delete();

        return redirect()->route('menus.index')->with('swal', [
            'message' => 'Menu deleted successfully!',
            'type' => 'success'
        ]);
    }

    public function restore($menuId){

        $menu = Menu::withTrashed()->findOrFail($menuId);
        $menu->restore();
        return redirect()->route('menus.index')->with('success', 'Menu restored successfully!');
    }

}
