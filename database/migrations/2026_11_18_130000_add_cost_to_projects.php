<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Free-text reference cost of a project ("Từ 25 triệu", "Liên hệ báo giá"); empty hides it. */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('projects', 'cost')) {
            Schema::table('projects', function (Blueprint $table): void {
                $table->string('cost')->nullable()->after('duration');
            });
        }
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->dropColumn('cost');
        });
    }
};
