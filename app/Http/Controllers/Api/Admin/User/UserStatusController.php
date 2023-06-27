<?php

namespace App\Http\Controllers\Api\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\UserService;

class UserStatusController extends Controller
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
