<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePermissionsRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->hasRole('admin');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:100|unique:permissions,name',  
            'menus' => 'required|array', 
            'menus.*' => 'exists:menus,id', 
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The permission name is required.',
            'name.unique' => 'This permission name has already been taken.',
            'menus.*.exists' => 'The selected menu does not exist.',
            'menus.required_if' => 'You must select at least one menu.',
        ];
    }
}
