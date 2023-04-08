<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ProfileController extends Controller
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ValidationException
     */
    public function update(User $user): JsonResponse
    {
        // Validate the request
        $this->validate(request(), [
            'name' => ['required', 'string', 'max:255'],
            'role_name' => ['required', 'string']
        ]);

        $user->setAttribute('name', request()->get('name'));
        $user->syncRoles(request()->get('role_name'));
        $user->save();

        return response()->json(['message' => 'Profile updated successfully']);
    }
}
