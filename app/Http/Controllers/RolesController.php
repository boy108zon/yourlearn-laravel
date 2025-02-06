<?php

namespace App\Http\Controllers;

use App\DataTables\RolesDataTable;
use App\Models\Role;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Menu;
use App\Services\RoleActionService;

class RolesController extends Controller
{
    protected $roleActionService;

    public function __construct(RoleActionService $roleActionService)
    {
        $this->middleware('auth');
        $this->roleActionService = $roleActionService;
    }

    public function index(RolesDataTable $dataTable)
    {
        $user = auth()->user();
        $actions = $this->roleActionService->getActions($user);
        return $dataTable->render('roles.index',compact('actions')); 
    }
    
    public function create(Role $role)
    {
        $menus = Menu::all();
        $assignedMenus = $role->menus->pluck('id')->toArray(); 
        return view('roles.create',compact('menus', 'assignedMenus'));
    }

    
    public function store(CreateRoleRequest $request)
    {
        
        $validated = $request->validated();
        $role = Role::create($validated);
        
        if (!empty($validated['menus'])) {
            $role->menus()->sync($validated['menus']); 
        }

        return redirect()->route('roles.index')->with('swal', [
            'message' => 'Role created successfully.',
            'type' => 'success'
        ]);
    }


    public function edit(Role $role)
    {
        $menus = Menu::all();
        $assignedMenus = $role->menus->pluck('id')->toArray(); 
        return view('roles.edit', compact('role', 'menus', 'assignedMenus'));
    }

    
    public function update(UpdateRoleRequest $request, $id)
    {
        $validated = $request->validated();
        $role = Role::findOrFail($id);
        $role->update($validated);

        if (isset($validated['menus'])) {
            $role->menus()->sync($validated['menus']);
        }

        return redirect()->route('roles.index')->with('swal', [
            'message' => 'Role updated successfully!',
            'type' => 'success',
        ]);
    }

    public function destroy(Role $role)
    {
        $role->users()->detach();
        $role->menus()->detach();
        $role->delete();
        return redirect()->route('roles.index')->with('swal', [
            'message' => 'Role deleted successfully!',
            'type' => 'success'
        ]);
    }
}
