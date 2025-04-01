<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255', 
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'price' => 'required|numeric|min:0', 
            'description' => 'nullable|string',
            
            'is_active' => 'required|boolean', 
            'category_id' => 'required|array', 
            'category_id.*' => 'exists:categories,id', 
            
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'stock_quantity' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0', 
            
        ];
    }


    
    public function messages(): array
    {
        return [
            'name.required' => 'The product name is required.',
            'slug.unique' => 'The slug has already been taken.',
            'price.required' => 'Please provide a price for the product.',
            'price.min' => 'Price must be a non-negative number.',
            'category_id.required' => 'A category must be selected for the product.',
        ];
    }

    
    public function attributes(): array
    {
        return [
            'name' => 'product name',
            'slug' => 'product slug',
            'price' => 'product price',
            'description' => 'product description',
            'images' => 'product images URL',
            'is_active' => 'product status',
            'category_id' => 'category',
        ];
    }
}
