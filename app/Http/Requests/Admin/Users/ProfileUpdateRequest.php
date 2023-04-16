<?php

namespace App\Http\Requests\Admin\Users;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
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
    public function rules(Request $request): array
    {
        $id = $request->route('user')['id'];
        return [
            'name' => 'required|string|max:255',
            'role_name' => 'required|string',
            'document' => [
                'required',
                'integer',
                'digits_between:6,12',
                Rule::unique(User::class)->ignore($id),
            ],
            'document_type' => 'required|string|max:255',
            'phone' => 'required|integer|digits_between:6,12',
            'address' => 'required|string|max:255',
            'city_id' => 'required|integer',
        ];
    }
}
