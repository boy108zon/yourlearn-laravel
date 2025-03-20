<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePromoCodesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        $promocode = $this->route('promoCode'); 
        $id= !empty($promocode) ?  $promocode->id :  NULL; 
        return [
            'code'             => 'required|string|max:50|unique:promo_codes,code,' .$id,
            'discount_type'    => 'required|in:fixed,percentage',
            'discount_amount'   => 'required|numeric|min:0',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date', 
            'status'           => 'required|in:active,inactive',
            'products'         => 'required|array',
            'products.*'       => 'exists:products,id',
        ];
    }

    public function messages()
    {
        return [
            'code.required'             => 'Promo code is required.',
            'code.unique'               => 'Promo code must be unique.',
            'discount_type.required'    => 'Discount type is required.',
            'discount_type.in'          => 'Invalid discount type. Choose either fixed or percentage.',
            'discount_value.required'   => 'Discount value is required.',
            'discount_value.numeric'    => 'Discount value must be a valid number.',
            'discount_value.min'        => 'Discount value must be at least 0.',
            'start_date.required'       => 'Start date is required.',
            'start_date.date'           => 'Start date must be a valid date.',
            'start_date.after_or_equal' => 'Start date must be today or later.',
            'end_date.required'         => 'End date is required.',
            'end_date.date'             => 'End date must be a valid date.',
            'end_date.after'            => 'End date must be after the start date.',
            'status.required'           => 'Promo code status is required.',
            'status.in'                 => 'Invalid status. Choose either active or inactive.',
            'products.array'            => 'Products must be a valid array.',
            'products.*.exists'         => 'Each selected product must exist in the product catalog.',
        ];
    }
}
