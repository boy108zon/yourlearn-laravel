<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionsRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->hasRole('admin');
    }

    public function rules()
    {
        return [
            'assign_permissions' => 'required|array', 
            'assign_permissions.*.exists' => 'exists:permissions,id', 
        ];

        
    }

    public function messages()
    {
        return [
           'assign_permissions.required' => 'Please select at least one permission.',
           'assign_permissions.array' => 'The permissions field must be an array.',
           'assign_permissions.*.exists' => 'One or more selected permissions are invalid.',
        ];
    }
}
