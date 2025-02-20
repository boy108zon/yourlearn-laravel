<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class CreateMenuRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->hasRole('admin');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:menus,name',
            'title' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255|',
            'slug' => 'nullable|string|max:255|unique:menus,slug',
            'sequence' => 'nullable|integer',
            'status' => 'nullable|string|in:active,inactive',
            'parent_id' => 'nullable|in:0,' . implode(',', \App\Models\Menu::pluck('id')->toArray()),
            'icon' => 'nullable|string|max:255',
        ];
    }
}


