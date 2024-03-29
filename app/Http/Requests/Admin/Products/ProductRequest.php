<?php

namespace App\Http\Requests\Admin\Products;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

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
    public function rules(Request $request): array
    {
        // If the request is for updating a product, we need to ignore the current product id
        $id = $this->route('product')->id ?? null;
        return [
            'sku' => [
                'required',
                'numeric',
                Rule::unique(Product::class, 'sku')->ignore($id),
            ],
            'name' => 'required|string|min:3|max:100',
            'description' => 'required|string|min:3|max:500',
            'price' => 'required|numeric|min:0|max:100000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'stock' => 'required|numeric|min:0|max:1000',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ];
    }
}
