<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use App\Models\Category;

class UpdateCategoryRequest extends FormRequest
{
   
    public function authorize(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        $category = $this->route('category'); 
       
        return [
            'name'        => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',           
            'image_url'   => 'nullable|url|max:255',      
            'is_active'   => 'required',          
        ];
    }

   
    public function messages(): array
    {
        return [
            'name.required'        => 'The category name is required.',
            'name.string'          => 'The category name must be a string.',
            'name.max'             => 'The category name may not be greater than 255 characters.',
            'description.string'   => 'The description must be a string.',
            'slug.required'        => 'The category slug is required.',
            'slug.string'          => 'The category slug must be a string.',
            'slug.unique'          => 'The category slug must be unique.',
            'slug.max'             => 'The category slug may not be greater than 255 characters.',
            'image_url.url'        => 'The image URL must be a valid URL.',
            'image_url.max'        => 'The image URL may not be greater than 255 characters.',
            'is_active.required'   => 'The category status is required.',
            'is_active.boolean'    => 'The category status must be true or false.',
        ];
    }

   
    public function attributes(): array
    {
        return [
            'name'        => 'category name',
            'description' => 'category description',
            'slug'        => 'category slug',
            'image_url'   => 'category image URL',
            'is_active'   => 'category status',
        ];
    }
}
