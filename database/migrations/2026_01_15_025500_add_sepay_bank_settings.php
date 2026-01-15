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
        $sepaySettings = [
            ['key' => 'sepay_webhook_token', 'value' => '', 'group' => 'payment', 'type' => 'password'],
            ['key' => 'sepay_match_pattern', 'value' => 'SE', 'group' => 'payment', 'type' => 'text'],
            ['key' => 'sepay_bank_name', 'value' => 'MBBank', 'group' => 'payment', 'type' => 'text'],
            ['key' => 'sepay_bank_account', 'value' => '', 'group' => 'payment', 'type' => 'text'],
            ['key' => 'sepay_account_name', 'value' => '', 'group' => 'payment', 'type' => 'text'],
        ];

        foreach ($sepaySettings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'sepay_webhook_token',
            'sepay_match_pattern',
            'sepay_bank_name',
            'sepay_bank_account',
            'sepay_account_name',
        ])->delete();
    }
};
