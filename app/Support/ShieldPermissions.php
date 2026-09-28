<?php

namespace App\Support;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Creates the Filament Shield permissions for new admin resources, so a
 * deploy only needs `php artisan migrate`.
 */
final class ShieldPermissions
{
    private const ABILITIES = ['ViewAny', 'View', 'Create', 'Update', 'Delete', 'DeleteAny', 'Restore', 'ForceDelete', 'ForceDeleteAny', 'RestoreAny', 'Replicate', 'Reorder'];

    /**
     * @param  array<int, string>  $models  model class basenames, e.g. ['Page']
     */
    public static function grantToSuperAdmin(array $models): void
    {
        $permissions = collect(self::names($models))->map(fn (string $name): Permission => Permission::findOrCreate($name, 'admin'));

        Role::findOrCreate('super_admin', 'admin')->givePermissionTo($permissions);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * @param  array<int, string>  $models
     */
    public static function revoke(array $models): void
    {
        Permission::query()->where('guard_name', 'admin')->whereIn('name', self::names($models))->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * @param  array<int, string>  $models
     * @return array<int, string>
     */
    private static function names(array $models): array
    {
        return collect($models)->crossJoin(self::ABILITIES)->map(fn (array $pair): string => $pair[1].':'.$pair[0])->all();
    }
}
