<?php

namespace App\Http\Requests;

use App\Models\Brand;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandRequest extends FormRequest
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
        // if the request is for updating a brand, we need to ignore the current brand
        /**
         * @var Brand|null $id
         */
        $id = $this->route('brand')->id ?? null;
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Brand::class)->ignore($id),
            ],
        ];
    }
}
