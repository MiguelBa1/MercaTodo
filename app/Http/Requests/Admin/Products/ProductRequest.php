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
                'string',
                'max:20',
                Rule::unique(Product::class)->ignore($id),
            ],
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'stock' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ];
    }
}
