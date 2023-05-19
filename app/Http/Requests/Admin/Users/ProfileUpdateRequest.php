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
            'name' => 'required|string|max:100',
            'role_name' => 'required|string|exists:roles,name',
            'document' => [
                'required',
                'integer',
                'digits_between:6,12',
                Rule::unique(User::class)->ignore($id),
            ],
            'document_type' => 'required|string|max:50',
            'phone' => 'required|integer|digits_between:6,12',
            'address' => 'required|string|min:3|max:100',
            'city_id' => 'required|integer',
        ];
    }
}
