<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AdminProfileController extends Controller
{
    /**
     * @param ProfileUpdateRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(ProfileUpdateRequest $request, User $user): JsonResponse
    {
        $user->update($request->validated());
        $user->syncRoles($request->input('role_name'));
        $user->save();

        return response()->json(['message' => 'Profile updated successfully']);
    }
}
