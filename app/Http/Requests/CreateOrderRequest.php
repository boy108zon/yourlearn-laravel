<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'customer_name'    => 'required|string|max:255',
            'customer_email'   => 'required|email|max:255',
            'customer_phone'   => 'required|string|max:20',
            'customer_address' => 'required|string|max:1000',
            'total_price'      => 'required|numeric|min:0',
            'status'           => 'required|in:pending,completed,shipped,canceled',
            'products'         => 'required|array', 
            'products.*'       => 'exists:products,id', 
        ];
    }

    public function messages()
    {
        return [
            'customer_name.required' => 'Customer name is required.',
            'customer_email.required' => 'Customer email is required.',
            'customer_phone.required' => 'Customer phone number is required.',
            'customer_address.required' => 'Customer address is required.',
            'total_price.required' => 'Total price is required.',
            'status.required' => 'Order status is required.',
            'products.required' => 'At least one product must be selected for the order.',
            'products.*.exists' => 'Each product must exist in the product catalog.',
        ];
    }
}
