<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MakeTransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Change to authorization logic if needed
    }

    public function rules()
    {
        return [
            'amount' => 'required|numeric',
            'reference' => [
                'required',
                'string',
                'min:3',
                'max:20',
                Rule::unique('payment_requests', 'reference')->where('merchant_id', $this->merchant_id) // Adjust as needed
            ],
            'currency' => ['required', Rule::in(['BDT' /*'USD'*/])],
            'callback_url' => 'required|url',
            'cust_name' => 'required|min:3|max:50',
            'cust_email' => 'required|email',
            'cust_phone' => 'required|min:3|max:15',
            'cust_address' => 'nullable|min:3|max:100',
            'checkout_items' => 'sometimes|array',
            'note' => 'sometimes|string',
            'trx_number' => 'required|string|min:9|max:10|regex:/^[a-zA-Z0-9]+$/',
            'from_number'=> 'required',
            'payment_method' => 'required|in:bkash,nagad',
            'sim_number' => 'required|exists:modems,sim_number',
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => 'The amount field is required.',
            'amount.numeric' => 'The amount must be a valid number.',
            'reference.required' => 'The reference field is required.',
            'reference.string' => 'The reference must be a string.',
            'reference.min' => 'The reference must be at least 3 characters long.',
            'reference.max' => 'The reference must not exceed 20 characters.',
            'reference.unique' => 'The reference has already been taken for this merchant.',
            'currency.required' => 'The currency field is required.',
            'currency.in' => 'The currency must be either BDT.',
            'callback_url.required' => 'The callback URL is required.',
            'callback_url.url' => 'The callback URL must be a valid URL.',
            'cust_name.required' => 'Customer name is required.',
            'cust_name.min' => 'Customer name must be at least 3 characters long.',
            'cust_name.max' => 'Customer name must not exceed 50 characters.',
            'cust_email.required' => 'Customer email is required.',
            'cust_email.email' => 'Customer email must be a valid email address.',
            'cust_phone.required' => 'Customer phone is required.',
            'cust_phone.min' => 'Customer phone must be at least 3 characters long.',
            'cust_phone.max' => 'Customer phone must not exceed 15 characters.',
            'cust_address.min' => 'Customer address must be at least 3 characters long.',
            'cust_address.max' => 'Customer address must not exceed 100 characters.',
            'checkout_items.array' => 'Checkout items must be an array.',
            'note.string' => 'The note must be a string.',
            'trx_number.required' => 'Transaction number is required.',
            'trx_number.string' => 'Transaction number must be a string.',
            'trx_number.min' => 'Transaction number must be at least 9 characters long.',
            'trx_number.max' => 'Transaction number must not exceed 10 characters.',
            'trx_number.regex' => 'Transaction number must be alphanumeric.',
            'from_number.required' => 'From number is required.',
            'payment_method.required' => 'Payment method is required.',
            'payment_method.in' => 'Payment method must be either bkash or nagad.',
            'sim_number.required' => 'SIM number is required.',
            'sim_number.exists' => 'The SIM number does not exist in the modem records.',
        ];
    }
}
