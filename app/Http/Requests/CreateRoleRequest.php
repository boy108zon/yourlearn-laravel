<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRoleRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->hasRole('admin');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'badge_color' => 'nullable|string|max:30',
            'menus' => 'required|array', 
            'menus.*' => 'exists:menus,id', 
        ];
    }

    public function messages()
    {
        return [
            'menus.*.exists' => 'The selected menu does not exist.',
            'menus.required_if' => 'You must select at least one menu.',
        ];
    }
}
