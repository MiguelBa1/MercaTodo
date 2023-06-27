<?php

namespace App\Http\Controllers\Api\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\ProfileUpdateRequest;
use App\Models\User;
use App\Services\User\UserService;

class ProfileController extends Controller
{
    /**
     * @param ProfileUpdateRequest $request
     * @param User $user
     * @param UserService $userService
     * @return void
     */
    public function update(ProfileUpdateRequest $request, User $user, UserService $userService): void
    {
        $userService->updateProfile($user, $request->validated());
    }
}
