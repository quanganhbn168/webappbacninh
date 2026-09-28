<?php

use App\Support\ShieldPermissions;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private const MODELS = ['Template', 'TemplateCategory', 'ThemeFeature', 'ProjectCategory'];

    public function up(): void
    {
        ShieldPermissions::grantToSuperAdmin(self::MODELS);
    }

    public function down(): void
    {
        ShieldPermissions::revoke(self::MODELS);
    }
};
