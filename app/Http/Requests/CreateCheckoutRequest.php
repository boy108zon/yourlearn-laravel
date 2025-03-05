<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCheckoutRequest extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255', 
            'shipping_address' => 'required|string|max:500',
            'billing_address' => 'required|string|max:500', 
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|min:5|max:10',

            
            'payment_method' => 'required|in:credit_card,debit_card,paypal', 
           
        ];
    }

    
    public function messages()
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.email' => 'Please enter a valid email address.',
            'shipping_address.required' => 'Shipping address is required.',
            'billing_address.required' => 'Billing address is required.',
            'country.required' => 'Country is required.',
            'state.required' => 'State is required.',
            'zip.required' => 'Zip code is required.',
            'payment_method.required' => 'Please select a payment method.',
            'cc_name.required_if' => 'Name on card is required when selecting credit or debit card.',
            'cc_number.required_if' => 'Credit card number is required when selecting credit or debit card.',
            'cc_expiration.required_if' => 'Expiration date is required when selecting credit or debit card.',
            'cc_cvv.required_if' => 'CVV is required when selecting credit or debit card.',
        ];
    }
}
