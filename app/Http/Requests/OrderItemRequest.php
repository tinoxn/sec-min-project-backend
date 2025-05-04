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
            'order_id' => 'required|exists:orders,id', // Must refer to a valid order
            'product' => 'required|string|max:255', // Product name validation
            'quantity' => 'required|integer|min:1', // Quantity must be at least 1
            'price' => 'required|numeric|min:0', // Price must be a positive number
            'total' => 'required|numeric|min:0', // Total price for the item
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'The order ID is required.',
            'order_id.exists' => 'The order must exist in the database.',
            'product.required' => 'The product name is required.',
            'quantity.required' => 'The quantity is required.',
            'price.required' => 'The price is required.',
            'total.required' => 'The total amount is required.',
        ];
    }
}