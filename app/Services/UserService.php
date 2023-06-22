<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getAllUsersExceptCurrent(int $currentUserId): LengthAwarePaginator
    {
        return User::with([
            'roles:id,name',
            'city:id,name'
        ])
            ->select(
                'id',
                'name',
                'surname',
                'email',
                'document',
                'document_type',
                'phone',
                'address',
                'status',
                'city_id'
            )
            ->whereNot('id', $currentUserId)
            ->latest('id')
            ->paginate(10);
    }

    public function getUserDataForEdit(User $user): array
    {
        $userData = $user->withoutRelations()->toArray();
        $userData['role_name'] = $user->roles->first()->name;
        $userData['city_name'] = $user->city->name;
        $userData['department_id'] = $user->city->department_id;

        return $userData;
    }

    public function registerUser(array $data): User
    {
        /** @var User $user */
        $user = User::query()->create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'document' => $data['document'],
            'document_type' => $data['document_type'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'city_id' => $data['city_id'],
        ]);

        $user->assignRole('customer');

        return $user;
    }

    public function updateProfile(User $user, array $data): void
    {
        $user->update($data);
        $user->syncRoles($data['role_name']);
        $user->save();
    }

    public function updateStatus(User $user): void
    {
        $user->status = !$user->getRawOriginal('status');
        $user->save();
    }

    public function updatePassword(User $user, string $password): void
    {
        $user->setAttribute('password', bcrypt($password));
        $user->save();
    }
}
