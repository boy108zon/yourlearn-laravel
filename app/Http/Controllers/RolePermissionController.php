<?php
namespace App\Http\Controllers;

use App\DataTables\PermissionsDataTable;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\CreatePermissionsRequest;
use App\Http\Requests\UpdatePermissionsRequest;
use App\Http\Requests\ChangePermissionNameRequest;
use Illuminate\Support\Str;
use App\Models\Menu; 
use App\Services\PermissionActionService;

class RolePermissionController extends Controller
{
    protected $permissionActionService;

    public function __construct(PermissionActionService $permissionActionService)
    {
        $this->middleware('auth');
        $this->permissionActionService = $permissionActionService;
    }

    
    public function index(PermissionsDataTable $dataTable)
    {
        $user = auth()->user();
        $actions = $this->permissionActionService->getActions($user);
        return $dataTable->render('permissions.index',compact('actions')); 
    }

    public function create(Role $role)
    {
        $menus = Menu::all();  
        return view('permissions.create', compact('menus'));
   }

   public function edit(Permission $permission)
    {
      return view('permissions.edit-permission', compact('permission'));
   }

   public function Permissions($roleId)
   {
        
        $role = Role::with('permissions')->findOrFail($roleId);
        $menus = Menu::with('children')->where('parent_id', 0)->get();
        $permissions = Permission::all();
        $assignedPermissions = $role->permissions->pluck('id')->toArray();

        $assignedMenus = [];
        foreach ($role->permissions as $permission) {
            $assignedMenus[$permission->id] = $permission->menus->pluck('id')->toArray();
        }

        return view('permissions.manage-permissions', compact('role', 'permissions', 'menus', 'assignedMenus', 'assignedPermissions'));
   }
   
    
    public function update(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);
    
        $request->validate([
            'permissions' => 'array|exists:permissions,id', 
        ]);
    
        $role->permissions()->sync($request->permissions); 
    
        return redirect()->route('roles.index')->with('swal', [
            'message' => 'Permissions have been successfully assigned to the role.',
            'type' => 'success',
        ]);
    }
    
    public function store(CreatePermissionsRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);
        $permission = Permission::create($validated);

        if ($request->has('menus')) {
            $permission->menus()->sync($request->menus); 
        }

        return back()->with('swal', [
            'message' => 'Permission created successfully.',
            'type' => 'success'
        ]);
    }
    
    public function changeName(ChangePermissionNameRequest $request, $permissionId)
    {
       
        $validated = $request->validated();
        $permission = Permission::findOrFail($permissionId);
           
        $permission->update($validated);

        return redirect()->route('permissions.index')->with('swal', [
            'message' => 'Saved successfully',
            'type' => 'success',
        ]);
       
    }

    public function updateMenus(UpdatePermissionsRequest $request, $roleId)
    {

        $validated = $request->validated();
        $role = Role::findOrFail($roleId);
    
        $selectedPermissions = $request->input('assign_permissions', []); 
        $role->permissions()->sync($selectedPermissions);

        return back()->with('swal', [
            'message' => 'Saved successfully.',
            'type' => 'success'
        ]);
    }
}
