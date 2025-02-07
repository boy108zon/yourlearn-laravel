<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;


Route::get('/', function () {
    #echo bcrypt('123123');exit;
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('users', [App\Http\Controllers\UsersController::class, 'index'])->name('users.index')->middleware('permission:list-users');
Route::get('users/create', [App\Http\Controllers\UsersController::class, 'create'])->name('users.create')->middleware('permission:create-user');
Route::put('users/store', [App\Http\Controllers\UsersController::class, 'store'])->name('users.store')->middleware('permission:create-user');
Route::get('users/{user}/edit', [App\Http\Controllers\UsersController::class, 'edit'])->name('users.edit')->middleware('permission:edit-user');
Route::put('users/{user}', [App\Http\Controllers\UsersController::class, 'update'])->name('users.update')->middleware('permission:edit-user');
Route::delete('users/{user}', [App\Http\Controllers\UsersController::class, 'destroy'])->name('users.destroy')->middleware('permission:remove-user');
Route::get('users/{user}/reset-password', [App\Http\Controllers\UsersController::class, 'resetPassword'])->name('users.resetPassword')->middleware('permission:reset-password-for-users');
Route::delete('users/{user}', [App\Http\Controllers\UsersController::class, 'destroy'])->name('users.destroy')->middleware('permission:remove-user');
Route::put('/users/{user}/reset-password', [App\Http\Controllers\UsersController::class, 'resetPasswordUpdate'])->name('users.reset-password')->middleware('permission:reset-password-for-users');


Route::get('roles', [App\Http\Controllers\RolesController::class, 'index'])->name('roles.index')->middleware('permission:list-roles');
Route::get('roles/create', [App\Http\Controllers\RolesController::class, 'create'])->name('roles.create')->middleware('permission:create-role');
Route::put('roles/store', [App\Http\Controllers\RolesController::class, 'store'])->name('roles.store')->middleware('permission:create-role');
Route::get('roles/{role}/edit', [App\Http\Controllers\RolesController::class, 'edit'])->name('roles.edit')->middleware('permission:edit-role');
Route::put('roles/{role}', [App\Http\Controllers\RolesController::class, 'update'])->name('roles.update')->middleware('permission:edit-role');
Route::delete('roles/{role}', [App\Http\Controllers\RolesController::class, 'destroy'])->name('roles.destroy')->middleware('permission:remove-role');


Route::get('roles/permissions/create', [App\Http\Controllers\RolePermissionController::class, 'create'])->name('permissions.create')->middleware('permission:create-permissions');
Route::put('permissions/store', [App\Http\Controllers\RolePermissionController::class, 'store'])->name('permissions.store')->middleware('permission:create-permissions');
Route::get('roles/{role}/permissions', [App\Http\Controllers\RolePermissionController::class, 'permissions'])->name('roles.permissions')->middleware('permission:assign-permissions');
Route::put('permissions/{roleId}/update-menus', [App\Http\Controllers\RolePermissionController::class, 'updateMenus'])->name('permissions.updateMenus')->middleware('permission:assign-permissions');

Route::get('permissions', [App\Http\Controllers\RolePermissionController::class, 'index'])->name('permissions.index');


Route::post('/', [App\Http\Controllers\RolePermissionController::class, 'index'])->name('index');
    Route::get('create', [App\Http\Controllers\RolePermissionController::class, 'create'])->name('create')->middleware('permission:create-permissions');
    Route::put('store', [App\Http\Controllers\RolePermissionController::class, 'store'])->name('store')->middleware('permission:create-permissions');
    Route::get('{role}/permissions', [App\Http\Controllers\RolePermissionController::class, 'permissions'])->name('permissions')->middleware('permission:assign-permissions');
    Route::put('{roleId}/update-menus', [App\Http\Controllers\RolePermissionController::class, 'updateMenus'])->name('updateMenus')->middleware('permission:assign-permissions');
    
    Route::get('{permission}', [App\Http\Controllers\RolePermissionController::class, 'edit'])->name('edit')->middleware('permission:edit-permission');
    Route::get('{permission}/edit', [App\Http\Controllers\RolePermissionController::class, 'edit'])->name('edit')->middleware('permission:edit-permission');
    
    Route::put('{permissionId}/changename', [App\Http\Controllers\RolePermissionController::class, 'changeName'])->name('changename')->middleware('permission:edit-permission');
    Route::delete('{permission}', [App\Http\Controllers\RolePermissionController::class, 'destroy'])->name('destroy')->middleware('permission:remove-permission');
    
