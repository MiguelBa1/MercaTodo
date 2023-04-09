<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUpdatePasswordRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PasswordController extends Controller
{
    /**
     * @param AdminUpdatePasswordRequest $request
     * @param User $user
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function update(AdminUpdatePasswordRequest $request, User $user): JsonResponse
    {
        $user->setAttribute('password', bcrypt(request()->get('password')));
        $user->save();

        return response()->json(['message' => 'Password updated successfully']);
    }
}
