<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserStatusController extends Controller
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
