<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->hasRole('admin');
    }

    public function rules()
    {
        $menuId = $this->route('menu'); 
        
        return [
            'name' => 'required|string|max:255|unique:menus,name,' . $menuId->id, 
            'title' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255,' . $menuId->id, 
            'slug' => 'nullable|string|max:255|unique:menus,slug,' . $menuId->id,
            'sequence' => 'nullable|integer',
            'status' => 'nullable|string|in:active,inactive',
            'parent_id' => 'required',
            'icon' => 'nullable|string|max:255',
        ];
    }
}
