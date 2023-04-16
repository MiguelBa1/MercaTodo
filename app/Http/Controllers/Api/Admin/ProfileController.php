<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    /**
     * @param UpdateProfileRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateProfileRequest $request, User $user): JsonResponse
    {
        $user->update($request->validated());
        $user->syncRoles($request->input('role_name'));
        $user->save();

        return response()->json(['message' => 'Profile updated successfully']);
    }
}
