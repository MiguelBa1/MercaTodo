<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CartStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];

        if ($this->has('product_id') && is_numeric($this->input('product_id'))) {
            $product = Product::query()->find($this->input('product_id'));
            if ($product) {
                $rules['quantity'][] = 'max:' . $product->getAttribute('stock');
            }
        }

        return $rules;
    }
}
