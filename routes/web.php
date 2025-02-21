<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\MenusController;
use App\Http\Controllers\LogActivityController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Auth::routes();

// Home Route
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');

// User Routes with Permissions
Route::prefix('users')->name('users.')->middleware('permission:list-users')->group(function() {
    Route::get('/', [UsersController::class, 'index'])->name('index');
    Route::get('create', [UsersController::class, 'create'])->name('create')->middleware('permission:create-user');
    Route::put('store', [UsersController::class, 'store'])->name('store')->middleware('permission:create-user');
    Route::get('{user}/edit', [UsersController::class, 'edit'])->name('edit')->middleware('permission:edit-user');
    Route::put('{user}', [UsersController::class, 'update'])->name('update')->middleware('permission:edit-user');
    Route::delete('{user}', [UsersController::class, 'destroy'])->name('destroy')->middleware('permission:remove-user');
    Route::get('{user}/reset-password', [UsersController::class, 'resetPassword'])->name('resetPassword')->middleware('permission:reset-password-for-users');
    Route::put('{user}/reset-password', [UsersController::class, 'resetPasswordUpdate'])->name('reset-password')->middleware('permission:reset-password-for-users');
});

// Role Routes with Permissions
Route::prefix('roles')->name('roles.')->middleware('permission:list-roles')->group(function() {
    Route::get('/', [RolesController::class, 'index'])->name('index');
    Route::get('create', [RolesController::class, 'create'])->name('create')->middleware('permission:create-role');
    Route::put('store', [RolesController::class, 'store'])->name('store')->middleware('permission:create-role');
    Route::get('{role}/edit', [RolesController::class, 'edit'])->name('edit')->middleware('permission:edit-role');
    Route::put('{role}', [RolesController::class, 'update'])->name('update')->middleware('permission:edit-role');
    Route::delete('{role}', [RolesController::class, 'destroy'])->name('destroy')->middleware('permission:remove-role');
    Route::get('{role}/permissions', [RolePermissionController::class, 'permissions'])->name('permissions')->middleware('permission:assign-permissions');

});

// Role Permission Routes
Route::prefix('roles/permissions')->name('permissions.')->middleware('permission:list-permission')->group(function() {
    Route::get('/', [RolePermissionController::class, 'index'])->name('index');
    Route::get('create', [RolePermissionController::class, 'create'])->name('create')->middleware('permission:create-permissions');
    Route::put('store', [RolePermissionController::class, 'store'])->name('store')->middleware('permission:create-permissions');
    Route::get('{role}/permissions', [RolePermissionController::class, 'permissions'])->name('permissions')->middleware('permission:assign-permissions');
    Route::put('{roleId}/update-menus', [RolePermissionController::class, 'updateMenus'])->name('updateMenus')->middleware('permission:assign-permissions');
    
    Route::get('{permission}', [RolePermissionController::class, 'edit'])->name('edit')->middleware('permission:edit-permission');
    Route::get('{permission}/edit', [RolePermissionController::class, 'edit'])->name('edit')->middleware('permission:edit-permission');
    
    Route::put('{permissionId}/changename', [RolePermissionController::class, 'changeName'])->name('changename')->middleware('permission:edit-permission');
    Route::delete('{permission}', [RolePermissionController::class, 'destroy'])->name('destroy')->middleware('permission:remove-permission');
    
});

Route::prefix('menus')->name('menus.')->middleware('permission:list-menu')->group(function () {
    Route::get('/', [MenusController::class, 'index'])->name('index');
    Route::get('create', [MenusController::class, 'create'])->name('create')->middleware('permission:create-menu');
    Route::post('store', [MenusController::class, 'store'])->name('store')->middleware('permission:create-menu');
    Route::get('{menu}/edit', [MenusController::class, 'edit'])->name('edit')->middleware('permission:edit-menu');
    Route::put('{menu}', [MenusController::class, 'update'])->name('update')->middleware('permission:edit-menu');
    Route::delete('{menu}', [MenusController::class, 'destroy'])->name('destroy')->middleware('permission:remove-menu');
});


Route::prefix('log-activity')->name('log-activity.')->middleware('permission:log-activity')->group(function () {
    Route::get('/', [LogActivityController::class, 'index'])->name('index');
});
