<?php

namespace App\Http\Controllers\Api\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\PasswordUpdateRequest;
use App\Models\User;
use App\Services\User\UserService;

class PasswordController extends Controller
{
    /**
     * @param PasswordUpdateRequest $request
     * @param UserService $userService
     * @param User $user
     * @return void
     */
    public function update(PasswordUpdateRequest $request, UserService $userService, User $user): void
    {
        $userService->updatePassword($user, $request->validated()['password']);
    }
}
