<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AdminUserStatusController extends Controller
{
    /**
     * @param User $user
     * @return JsonResponse
     */
    public function update(User $user): JsonResponse
    {
        $user->setAttribute('status', !$user->getRawOriginal('status'));
        $user->save();

        return response()->json(['message' => 'User status updated successfully']);
    }
}
