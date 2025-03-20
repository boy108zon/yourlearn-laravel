<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePromoCodesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'code'           => 'required|string|max:255|unique:promo_codes,code',
            'discount_type'  => 'required|in:fixed,percentage',
            'discount_amount' => 'required|numeric|min:0',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after:start_date',
            'status'         => 'required|in:active,inactive',
            'products'       => 'required|array',
            'products.*'     => 'exists:products,id',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'The promo code is required.',
            'discount_type.required' => 'The discount type is required.',
            'discount_value.required' => 'The discount value is required.',
            'start_date.required' => 'The start date is required.',
            'end_date.required' => 'The end date is required.',
            'status.required' => 'The status is required.',
            'products.required' => 'You must select at least one product.',
            'products.*.exists' => 'Each product must exist in the database.',
        ];
    }
}
