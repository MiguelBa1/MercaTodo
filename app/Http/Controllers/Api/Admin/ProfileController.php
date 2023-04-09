<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ProfileController extends Controller
{
    /**
     * @param UpdateProfileRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateProfileRequest $request, User $user): JsonResponse
    {
        $user->setAttribute('name', $request->input('name'));
        $user->syncRoles($request->input('role_name'));
        $user->save();

        return response()->json(['message' => 'Profile updated successfully']);
    }
}
