<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        // If the user role changed, then make a log
        if (!$user->hasRole(request()->get('role_name'))) {
            Log::warning('[ROLE]', [
                'admin_id' => auth()->user()->getAttribute('id'),
                'user_id' => $user->getAttribute('id'),
                'user_name' => $user->getAttribute('name'),
                'user_email' => $user->getAttribute('email'),
                'old_role' => $user->getRoleNames()[0],
                'new_role' => request()->get('role_name')
            ]);
        }

        // Update the profile
        $user->setAttribute('name', request()->get('name'));
        $user->syncRoles(request()->get('role_name'));
        $user->save();

        return response()->json(['message' => 'Profile updated successfully']);
    }
}
