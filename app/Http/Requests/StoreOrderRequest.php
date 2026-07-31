<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Get the custom validation rules after base validation.
     */
    public function after(): array
    {
        return [
            function ($validator) {
                if ($this->has('items') && is_array($this->items)) {
                    foreach ($this->items as $index => $item) {
                        if (empty($item['product_id']) || empty($item['quantity'])) {
                            continue;
                        }
                        $product = Product::find($item['product_id']);
                        if ($product && $item['quantity'] > $product->stock_quantity) {
                            $validator->errors()->add(
                                "items.{$index}.quantity",
                                "Not enough stock for {$product->name}. Only {$product->stock_quantity} available."
                            );
                        }
                    }
                }
            }
        ];
    }
}
