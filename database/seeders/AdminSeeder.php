<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Role for admin guard
        $role = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'admin'
        ]);

        // Create Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@webappbacninh.vn'],
            [
                'name' => 'Quang Anh Admin',
                'password' => Hash::make('Admin@123'),
            ]
        );

        $admin->assignRole($role);
    }
}
