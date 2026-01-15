<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Find duplicate entries for /tools/ngan-hang
        $duplicates = DB::table('mini_apps')
            ->where('link', '/tools/ngan-hang')
            ->orderBy('id', 'desc') // Keep the latest one or handle logic
            ->get();

        if ($duplicates->count() > 1) {
            // Keep the first one found (or latest), delete others
            // Let's keep the one with the highest ID (latest)
            $latestId = $duplicates->first()->id;
            
            DB::table('mini_apps')
                ->where('link', '/tools/ngan-hang')
                ->where('id', '!=', $latestId)
                ->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action needed for down as this is a cleanup
    }
};
