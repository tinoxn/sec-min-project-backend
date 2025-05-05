<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // You can add authorization logic here if needed
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'The order ID is required.',
            'order_id.exists' => 'The order must exist in the database.',
            'product_id.required' => 'The product name is required.',
            'quantity.required' => 'The quantity is required.',
            'price.required' => 'The price is required.',
            'total.required' => 'The total amount is required.',
        ];
    }
}
