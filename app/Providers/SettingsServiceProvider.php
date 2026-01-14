<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('settings')) {
            try {
                $settings = Setting::all();

                foreach ($settings as $setting) {
                    if ($setting->group === 'mail') {
                        $this->setConfigMail($setting);
                    }
                    if ($setting->group === 'payment') {
                        $this->setConfigPayment($setting);
                    }
                }
            } catch (\Exception $e) {
                // Settings table might not exist in fresh migrations or some other error
            }
        }
    }

    private function setConfigMail($setting)
    {
        if (empty($setting->value)) return;

        switch ($setting->key) {
            case 'mail_mailer':
                Config::set('mail.default', $setting->value);
                break;
            case 'mail_host':
                Config::set('mail.mailers.smtp.host', $setting->value);
                break;
            case 'mail_port':
                Config::set('mail.mailers.smtp.port', $setting->value);
                break;
            case 'mail_username':
                Config::set('mail.mailers.smtp.username', $setting->value);
                break;
            case 'mail_password':
                Config::set('mail.mailers.smtp.password', $setting->value);
                break;
            case 'mail_encryption':
                Config::set('mail.mailers.smtp.encryption', $setting->value);
                break;
            case 'mail_from_address':
                Config::set('mail.from.address', $setting->value);
                break;
            case 'mail_from_name':
                Config::set('mail.from.name', $setting->value);
                break;
        }
    }

    private function setConfigPayment($setting)
    {
        if (empty($setting->value)) return;

        switch ($setting->key) {
            case 'payment_momo_api_key':
                Config::set('payment.momo.api_key', $setting->value);
                break;
            case 'payment_zalo_app_id':
                Config::set('payment.zalo.app_id', $setting->value);
                break;
            case 'payment_sepay_api_key':
                Config::set('payment.sepay.api_key', $setting->value);
                break;
            case 'payment_vietqr_client_id':
                Config::set('payment.vietqr.client_id', $setting->value);
                break;
            case 'payment_vietqr_api_key':
                Config::set('payment.vietqr.api_key', $setting->value);
                break;
        }
    }
}
