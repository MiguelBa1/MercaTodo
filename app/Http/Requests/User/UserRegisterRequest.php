<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRegisterRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'surname' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email',
            'password' => ['required', Password::defaults(), 'confirmed'],
            'document' => 'required|integer|digits_between:6,12|unique:users,document',
            'document_type' => 'required|string|max:255',
            'phone' => 'required|integer|digits_between:6,12',
            'address' => 'required|string|max:255',
            'city_id' => 'required|integer',
        ];
    }
}
