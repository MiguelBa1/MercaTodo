<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        // if the request is for updating a category, we need to ignore the current category
        /**
         * @var Category|null $id
         */
        $id = $this->route('category')->id ?? null;
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Category::class)->ignore($id),
            ],
        ];
    }
}
