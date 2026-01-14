<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function create(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (!empty($data['role'])) {
            $user->assignRole($data['role']);
        }

        return $user;
    }

    public function update(User $user, array $data): User
    {
        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        if (!empty($data['role'])) {
            $user->syncRoles([$data['role']]);
        }

        return $user;
    }

    public function delete(User $user): bool
    {
        if ($user->hasRole('super_admin') && User::role('super_admin')->count() <= 1) {
            return false;
        }
        
        return $user->delete();
    }

    public function bulkDelete(array $ids): int
    {
        $users = User::whereIn('id', $ids)->get();
        $deleted = 0;
        
        foreach ($users as $user) {
            if (!($user->hasRole('super_admin') && User::role('super_admin')->count() <= 1)) {
                $user->delete();
                $deleted++;
            }
        }

        return $deleted;
    }
}
