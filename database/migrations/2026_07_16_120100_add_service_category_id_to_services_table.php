<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('services', 'service_category_id')) {
            return;
        }

        Schema::table('services', function (Blueprint $table): void {
            $table->foreignId('service_category_id')
                ->nullable()
                ->after('id')
                ->constrained('service_categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('services', 'service_category_id')) {
            return;
        }

        Schema::table('services', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('service_category_id');
        });
    }
};
