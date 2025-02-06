<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    public function authorize(): bool
    {
        //return true;
        return auth()->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user');
            return [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $userId,
                'password' => 'nullable|string|min:6|confirmed', 
                'extension' => 'required|string|max:2',
                'mobile' => 'required|digits_between:10,15',
                'telephone' => 'nullable|digits_between:10,15',
                'address' => 'required|string|max:255',
                'country_id' => 'required|exists:countries,id',
                'state_id' => 'required|exists:states,id',
                'city_id' => 'required|string|max:100',
                'pincode' => 'nullable|string|min:5', 
                'alternate_no' => 'nullable|string|min:10', 
                'extension' => 'nullable|string|min:4', 
                'file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
                'roles' => ['required', 'array', 'min:1'],
                'roles.*' => ['exists:roles,id'],
            ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'The first name is mandatory.',
            'last_name.required' => 'Please provide your last name.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'The email is already taken by another user.',
            'extension.required' => 'The extension number is required.',
            'mobile.required' => 'The mobile number is required.',
            'address.required' => 'Please provide your full address.',
            'pincode.required' => 'Pincode is required.',
            'country_id.required' => 'Please select a country.',
            'state_id.required' => 'Please select a state.',
            'city_id.required' => 'Please provide the city name.',
        ];
    }
    
}
