<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\ProfileUpdateRequest;
use App\Models\User;
use App\Services\UserService;

class AdminProfileController extends Controller
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
