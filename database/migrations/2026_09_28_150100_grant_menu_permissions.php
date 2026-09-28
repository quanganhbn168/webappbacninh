<?php

use App\Support\ShieldPermissions;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        ShieldPermissions::grantToSuperAdmin(['Menu']);
    }

    public function down(): void
    {
        ShieldPermissions::revoke(['Menu']);
    }
};
