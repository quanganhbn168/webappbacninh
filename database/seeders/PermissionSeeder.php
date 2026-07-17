<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'admin']);

        foreach (['manage settings', 'manage menus', 'manage leads', 'manage articles', 'manage projects', 'manage themes', 'manage services', 'manage users'] as $name) {
            $permission = Permission::firstOrCreate(['name' => $name, 'guard_name' => 'admin']);
            $role->givePermissionTo($permission);
        }
    }
}
