<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'surname' => 'required|string|max:100',
            'email' => [
                'email',
                'max:100',
                Rule::unique(User::class)->ignore($this->user()->id)
            ],
            'phone' => 'required|integer|digits_between:6,12',
            'address' => 'required|string|max:255',
            'city_id' => 'required|integer',
        ];
    }
}
