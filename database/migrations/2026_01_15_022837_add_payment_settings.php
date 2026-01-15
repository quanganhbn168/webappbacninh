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
        $paymentSettings = [
            // VNPay
            ['key' => 'vnpay_tmn_code', 'value' => '', 'group' => 'payment', 'type' => 'text'],
            ['key' => 'vnpay_hash_secret', 'value' => '', 'group' => 'payment', 'type' => 'password'],
            ['key' => 'vnpay_sandbox', 'value' => '1', 'group' => 'payment', 'type' => 'boolean'],

            // MoMo
            ['key' => 'momo_partner_code', 'value' => '', 'group' => 'payment', 'type' => 'text'],
            ['key' => 'momo_access_key', 'value' => '', 'group' => 'payment', 'type' => 'password'],
            ['key' => 'momo_secret_key', 'value' => '', 'group' => 'payment', 'type' => 'password'],

            // SePay
            ['key' => 'sepay_merchant_id', 'value' => '', 'group' => 'payment', 'type' => 'text'],
            ['key' => 'sepay_api_key', 'value' => '', 'group' => 'payment', 'type' => 'password'],

            // PayPal
            ['key' => 'paypal_client_id', 'value' => '', 'group' => 'payment', 'type' => 'text'],
            ['key' => 'paypal_client_secret', 'value' => '', 'group' => 'payment', 'type' => 'password'],
            ['key' => 'paypal_sandbox', 'value' => '1', 'group' => 'payment', 'type' => 'boolean'],

            // General
            ['key' => 'default_payment_provider', 'value' => 'vnpay', 'group' => 'payment', 'type' => 'select'],
            ['key' => 'payment_enabled_providers', 'value' => 'vnpay,momo,sepay,paypal', 'group' => 'payment', 'type' => 'text'],
        ];

        foreach ($paymentSettings as $setting) {
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
        DB::table('settings')->where('group', 'payment')->delete();
    }
};
