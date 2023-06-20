<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;

class AdminUserStatusController extends Controller
{
    /**
     * @param UserService $userService
     * @param User $user
     * @return void
     */
    public function update(UserService $userService, User $user): void
    {
        $userService->updateStatus($user);
    }
}
