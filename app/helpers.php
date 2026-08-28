<?php

use App\Domain\Settings\Actions\RenderTrackingCode;
use App\Domain\Site\Actions\ResolvePublicAssetUrl;
use App\Settings\ContactSettings;
use App\Settings\FaviconSettings;
use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;
use App\Settings\SocialSettings;
use App\Settings\TrackingSettings;
use App\Settings\WebsiteSettings;

if (! function_exists('site_settings')) {
    function site_settings(): array
    {
        static $values;

        if ($values !== null) {
            return $values;
        }

        $values = array_merge(config('site'), [
            'site_name' => config('site.name'),
            'company_name' => config('site.name'),
            'default_language' => 'vi',
            'site_logo_wide' => '',
            'site_logo_white' => '',
            'site_logo_square' => '',
            'site_favicon' => '',
            'favicon_short_name' => '',
            'favicon_maskable_icon' => '',
            'favicon_safari_mask_icon' => '',
            'favicon_theme_color' => '#0f172a',
            'favicon_background_color' => '#ffffff',
            'favicon_safari_mask_color' => '#0f172a',
            'favicon_generated_version' => '',
            'default_meta_title' => config('site.name'),
            'default_meta_description' => 'Thiết kế website theo nhu cầu doanh nghiệp, tối ưu SEO và chuyển đổi.',
            'default_meta_keywords' => 'thiết kế website, web Bắc Ninh, website doanh nghiệp',
            'default_og_image' => '',
            'google_site_verification' => '',
            'google_analytics_id' => '',
            'tracking_enabled' => false,
            'google_tag_id' => '',
            'head_code' => '',
            'body_start_code' => '',
            'body_end_code' => '',
            'page_meta' => [],
            'messenger' => '',
            'telegram' => '',
            'wechat' => '',
            'whatsapp' => '',
        ]);

        $mapping = [
            GeneralSettings::class => [
                'name' => 'name',
                'company_name' => 'company_name',
                'default_language' => 'default_language',
            ],
            WebsiteSettings::class => [
                'site_url' => 'site_url',
                'site_logo_wide' => 'site_logo_wide',
                'site_logo_white' => 'site_logo_white',
                'site_logo_square' => 'site_logo_square',
                'site_favicon' => 'site_favicon',
            ],
            SeoSettings::class => [
                'default_meta_title' => 'default_meta_title',
                'default_meta_description' => 'default_meta_description',
                'default_meta_keywords' => 'default_meta_keywords',
                'default_og_image' => 'default_og_image',
                'google_site_verification' => 'google_site_verification',
                'google_analytics_id' => 'google_analytics_id',
                'page_meta' => 'page_meta',
            ],
            ContactSettings::class => [
                'phone' => 'phone',
                'phone_href' => 'phone_href',
                'email' => 'email',
                'address' => 'address',
                'working_time' => 'working_time',
            ],
            SocialSettings::class => [
                'facebook' => 'facebook',
                'messenger' => 'messenger',
                'zalo' => 'zalo',
                'telegram' => 'telegram',
                'wechat' => 'wechat',
                'whatsapp' => 'whatsapp',
                'youtube' => 'youtube',
            ],
            TrackingSettings::class => [
                'tracking_enabled' => 'enabled',
                'google_tag_id' => 'google_tag_id',
                'head_code' => 'head_code',
                'body_start_code' => 'body_start_code',
                'body_end_code' => 'body_end_code',
            ],
            FaviconSettings::class => [
                'favicon_short_name' => 'short_name',
                'favicon_maskable_icon' => 'maskable_icon',
                'favicon_safari_mask_icon' => 'safari_mask_icon',
                'favicon_theme_color' => 'theme_color',
                'favicon_background_color' => 'background_color',
                'favicon_safari_mask_color' => 'safari_mask_color',
                'favicon_generated_version' => 'generated_version',
            ],
        ];

        try {
            foreach ($mapping as $class => $fields) {
                $settings = app($class);

                foreach ($fields as $key => $property) {
                    $values[$key] = $settings->{$property};
                }
            }

            $values['site_name'] = $values['name'];
            $values['site_description'] = $values['default_meta_description'];
            $values['meta_keywords'] = $values['default_meta_keywords'];
            $values['contact_name'] = $values['company_name'];
            $values['contact_phone'] = $values['phone'];
            $values['contact_email'] = $values['email'];
            $values['contact_address'] = $values['address'];
            $values['social_facebook'] = $values['facebook'];
            $values['social_youtube'] = $values['youtube'];
            $values['social_zalo'] = $values['zalo'];
        } catch (Throwable) {
            // During first deploy the settings migrations may not have run yet; defaults stay available.
        }

        return $values;
    }
}

if (! function_exists('site_asset_url')) {
    function site_asset_url(?string $path, string $fallback = ''): string
    {
        return app(ResolvePublicAssetUrl::class)->execute($path, $fallback);
    }
}

if (! function_exists('tracking_code')) {
    function tracking_code(string $position): string
    {
        $tracking = app(RenderTrackingCode::class)->execute();

        return match ($position) {
            'head' => $tracking->head,
            'body_start' => $tracking->bodyStart,
            'body_end' => $tracking->bodyEnd,
            default => '',
        };
    }
}

if (! function_exists('site_config')) {
    function site_config(?string $key = null, mixed $default = null): mixed
    {
        $settings = site_settings();

        if ($key === null) {
            return $settings;
        }

        return data_get($settings, $key, $default);
    }
}

if (! function_exists('site_page_seo')) {
    /** @return array<string, string> */
    function site_page_seo(string $key, array $fallback = []): array
    {
        $pages = site_config('page_meta', []);
        $configured = is_array($pages) && is_array($pages[$key] ?? null) ? $pages[$key] : [];

        return array_replace($fallback, array_filter($configured, static fn (mixed $value): bool => is_string($value) && trim($value) !== ''));
    }
}

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return site_config($key, $default);
    }
}

if (! function_exists('frontend_asset')) {
    function frontend_asset(string $path): string
    {
        return asset('frontend/'.ltrim($path, '/'));
    }
}

if (! function_exists('frontend_url')) {
    function frontend_url(string $path = ''): string
    {
        if (preg_match('~^(https?:)?//|^(mailto:|tel:|#)~', $path)) {
            return $path;
        }

        [$path, $fragment] = array_pad(explode('#', $path, 2), 2, null);
        $route = match ($path) {
            '', 'index.php' => route('home'),
            'gioi-thieu.php' => route('about'),
            'lien-he.php' => route('contact'),
            'bang-gia.php' => route('pricing'),
            'hop-tac-agency.php' => route('agency'),
            'thiet-ke-website.php' => route('services.index'),
            'website-doanh-nghiep.php' => route('services.show', 'website-doanh-nghiep'),
            'website-ban-hang.php' => route('services.show', 'website-ban-hang'),
            'landing-page.php' => route('services.show', 'landing-page'),
            'thiet-ke-lai-website.php' => route('services.show', 'thiet-ke-lai-website'),
            'kho-giao-dien.php' => route('themes.index'),
            'du-an.php' => route('projects.index'),
            'kien-thuc.php' => route('articles.index'),
            'dich-vu-van-hanh.php' => route('operations.index'),
            'hosting-bao-tri-website.php' => route('operations.show', 'hosting-bao-tri-website'),
            'quan-tri-dang-bai-website.php' => route('operations.show', 'quan-tri-dang-bai-website'),
            'seo-website.php' => route('operations.show', 'seo-website'),
            'noi-dung-facebook.php' => route('operations.show', 'noi-dung-facebook'),
            'nang-cap-tich-hop-website.php' => route('operations.show', 'nang-cap-tich-hop-website'),
            'do-luong-bao-cao-website.php' => route('operations.show', 'do-luong-bao-cao-website'),
            'chinh-sach-bao-mat.php' => route('legal.privacy'),
            'dieu-khoan-su-dung.php' => route('legal.terms'),
            'chinh-sach-bao-hanh.php' => route('legal.warranty'),
            'quy-trinh-thanh-toan.php' => route('legal.payment'),
            default => url($path),
        };

        return $fragment === null ? $route : $route.'#'.$fragment;
    }
}

if (! function_exists('absolute_url')) {
    function absolute_url(string $path = ''): string
    {
        return preg_match('~^https?://~', $path)
            ? $path
            : rtrim((string) site_config('site_url', config('app.url')), '/').'/'.ltrim($path, '/');
    }
}

if (! function_exists('themes')) {
    function themes(): array
    {
        return config('themes', []);
    }
    function projects(): array
    {
        return config('projects', []);
    }
    function articles(): array
    {
        return config('articles', []);
    }

    function theme_by_slug(string $slug): ?array
    {
        return collect(themes())->firstWhere('slug', $slug);
    }
    function project_by_slug(string $slug): ?array
    {
        return collect(projects())->firstWhere('slug', $slug);
    }
    function article_by_slug(string $slug): ?array
    {
        return collect(articles())->firstWhere('slug', $slug);
    }

    function theme_url(array $theme): string
    {
        return route('themes.show', $theme['slug']);
    }
    function project_url(array $project): string
    {
        return route('projects.show', $project['slug']);
    }
    function article_url(array $article): string
    {
        return route('articles.show', $article['slug']);
    }

    function related_themes(array $current, int $limit = 3): array
    {
        return collect(themes())->reject(fn (array $item) => $item['slug'] === $current['slug'])
            ->sortByDesc(fn (array $item) => (($item['industry'] === $current['industry']) ? 2 : 0) + (($item['type'] === $current['type']) ? 1 : 0) + (($item['featured'] ?? 0) / 100))
            ->take($limit)->values()->all();
    }

    function related_projects(array $current, int $limit = 3): array
    {
        return collect(projects())->reject(fn (array $item) => $item['slug'] === $current['slug'])
            ->sortByDesc(fn (array $item) => (($item['industry'] === $current['industry']) ? 2 : 0) + (($item['category'] === $current['category']) ? 1 : 0) + (($item['featured'] ?? 0) / 100))
            ->take($limit)->values()->all();
    }

    function related_articles(array $current, int $limit = 3): array
    {
        return collect(articles())->reject(fn (array $item) => $item['slug'] === $current['slug'])
            ->sortByDesc(fn (array $item) => (($item['category'] === $current['category']) ? 2 : 0) + (($item['featured'] ?? 0) / 100))
            ->take($limit)->values()->all();
    }

    function money(int|float $value): string
    {
        return number_format((float) $value, 0, ',', '.').'đ';
    }
}
