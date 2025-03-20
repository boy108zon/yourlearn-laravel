<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'status' => 'required|in:pending,processing,completed,shipped,delivered,canceled,returned,refunded',
            'total_price' => 'required|numeric|min:0',
            'shipping_address' => 'required|string|max:1000',
            'billing_address' => 'required|string|max:1000',
            'payment_method' => 'required|in:paypal,credit_card,bank_transfer',
            'tracking_number' => 'nullable|string|max:255',
            'order_products' => 'required|array', 
            'order_products.*.quantity' => 'required|integer|min:1',  
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'Customer first name is required.',
            'last_name.required' => 'Customer last name is required.',
            'email.required' => 'Customer email is required.',
            'status.required' => 'Order status is required.',
            'total_price.required' => 'Total price is required.',
            'shipping_address.required' => 'Shipping address is required.',
            'billing_address.required' => 'Billing address is required.',
            'payment_method.required' => 'Payment method is required.',
            'order_products.required' => 'At least one product must be selected.',
            'order_products.*.quantity.required' => 'Product quantity is required.',
        ];
    }
}
