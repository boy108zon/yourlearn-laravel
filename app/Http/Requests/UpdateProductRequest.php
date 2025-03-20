<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        $product=$this->route('product');

        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id, 
            'price' => 'required|numeric|min:0', 
            'stock_quantity'=>'required|numeric', 
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The product name is required.',
            'slug.unique' => 'The slug has already been taken.',
            'price.required' => 'Please provide a price for the product.',
            'price.min' => 'Price must be a non-negative number.',
            'stock_quantity.required' => 'Please choose quantity for the product.',
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
            'image_url' => 'product image URL',
            'is_active' => 'product status',
            'category_id' => 'category',
        ];
    }
}
