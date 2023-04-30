<?php

namespace App\Http\Requests\Admin\Products;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        return [
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->ignore($this->product),
            ],
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'stock' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ];
    }
}
