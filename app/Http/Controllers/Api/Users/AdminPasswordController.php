<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\PasswordUpdateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AdminPasswordController extends Controller
{
    /**
     * @param PasswordUpdateRequest $request
     * @param User $user
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function update(PasswordUpdateRequest $request, User $user): JsonResponse
    {
        $user->setAttribute('password', bcrypt(request()->get('password')));
        $user->save();

        return response()->json(['message' => 'Password updated successfully']);
    }
}
