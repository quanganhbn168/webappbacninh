<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Settings\ContactSettings;
use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;
use App\Settings\SocialSettings;
use App\Settings\WebsiteSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Spatie\LaravelSettings\Settings;

class SettingController extends Controller
{
    public function index(): View
    {
        return view('admin.settings.index', [
            'general' => app(GeneralSettings::class),
            'website' => app(WebsiteSettings::class),
            'seo' => app(SeoSettings::class),
            'contact' => app(ContactSettings::class),
            'social' => app(SocialSettings::class),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'general.name' => ['required', 'string', 'max:255'],
            'general.company_name' => ['nullable', 'string', 'max:255'],
            'general.default_language' => ['required', 'string', 'max:10'],

            'website.site_url' => ['required', 'url', 'max:255'],
            'website.site_logo_wide' => ['nullable', 'string', 'max:2048'],
            'website.site_logo_white' => ['nullable', 'string', 'max:2048'],
            'website.site_logo_square' => ['nullable', 'string', 'max:2048'],
            'website.site_favicon' => ['nullable', 'string', 'max:2048'],

            'seo.default_meta_title' => ['nullable', 'string', 'max:255'],
            'seo.default_meta_description' => ['nullable', 'string', 'max:500'],
            'seo.default_meta_keywords' => ['nullable', 'string', 'max:500'],
            'seo.default_og_image' => ['nullable', 'string', 'max:2048'],
            'seo.google_site_verification' => ['nullable', 'string', 'max:255'],
            'seo.google_analytics_id' => ['nullable', 'string', 'max:255'],

            'contact.phone' => ['required', 'string', 'max:50'],
            'contact.phone_href' => ['required', 'string', 'max:50'],
            'contact.email' => ['required', 'email', 'max:255'],
            'contact.address' => ['nullable', 'string', 'max:500'],
            'contact.working_time' => ['nullable', 'string', 'max:255'],

            'social.facebook' => ['nullable', 'string', 'max:2048'],
            'social.messenger' => ['nullable', 'string', 'max:2048'],
            'social.zalo' => ['nullable', 'string', 'max:2048'],
            'social.telegram' => ['nullable', 'string', 'max:2048'],
            'social.wechat' => ['nullable', 'string', 'max:2048'],
            'social.whatsapp' => ['nullable', 'string', 'max:2048'],
            'social.youtube' => ['nullable', 'string', 'max:2048'],
        ]);

        $this->save(app(GeneralSettings::class), $data['general'], ['name', 'company_name', 'default_language']);
        $this->save(app(WebsiteSettings::class), $data['website'], ['site_url', 'site_logo_wide', 'site_logo_white', 'site_logo_square', 'site_favicon']);
        $this->save(app(SeoSettings::class), $data['seo'], ['default_meta_title', 'default_meta_description', 'default_meta_keywords', 'default_og_image', 'google_site_verification', 'google_analytics_id']);
        $this->save(app(ContactSettings::class), $data['contact'], ['phone', 'phone_href', 'email', 'address', 'working_time']);
        $this->save(app(SocialSettings::class), $data['social'], ['facebook', 'messenger', 'zalo', 'telegram', 'wechat', 'whatsapp', 'youtube']);

        return back()->with('success', 'Đã cập nhật cấu hình bằng Spatie Settings.');
    }

    public function sendTestMail(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        try {
            Mail::raw('Đây là email kiểm tra kết nối SMTP từ WebApp Bắc Ninh.', function ($message) use ($request): void {
                $message->to($request->email)->subject('Test SMTP Connection - WebApp Bắc Ninh');
            });

            return back()->with('success', 'Gửi email test thành công! Hãy kiểm tra hộp thư đến hoặc spam.');
        } catch (\Throwable $exception) {
            return back()->with('error', 'Lỗi gửi mail: '.$exception->getMessage());
        }
    }

    /**
     * @param  array<string, mixed>  $values
     * @param  array<int, string>  $fields
     */
    private function save(Settings $settings, array $values, array $fields): void
    {
        foreach ($fields as $field) {
            $settings->{$field} = trim((string) ($values[$field] ?? ''));
        }

        $settings->save();
    }
}
