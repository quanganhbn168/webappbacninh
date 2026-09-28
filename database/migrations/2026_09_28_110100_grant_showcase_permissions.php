<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    private const MODELS = ['Template', 'TemplateCategory', 'ThemeFeature'];

    private const ABILITIES = ['ViewAny', 'View', 'Create', 'Update', 'Delete', 'DeleteAny', 'Restore', 'ForceDelete', 'ForceDeleteAny', 'RestoreAny', 'Replicate', 'Reorder'];

    public function up(): void
    {
        $permissions = collect($this->names())->map(fn (string $name): Permission => Permission::findOrCreate($name, 'admin'));

        Role::findOrCreate('super_admin', 'admin')->givePermissionTo($permissions);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        Permission::query()->where('guard_name', 'admin')->whereIn('name', $this->names())->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * @return array<int, string>
     */
    private function names(): array
    {
        return collect(self::MODELS)
            ->crossJoin(self::ABILITIES)
            ->map(fn (array $pair): string => $pair[1].':'.$pair[0])
            ->all();
    }
};
