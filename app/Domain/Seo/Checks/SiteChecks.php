<?php

namespace App\Domain\Seo\Checks;

use App\Domain\Seo\SeoCheck;
use App\Domain\Seo\SeoChecks;
use App\Domain\Seo\SeoContext;

/** Settings that affect every page; fixed once in the admin, not in an article. */
class SiteChecks implements SeoChecks
{
    public function title(): string
    {
        return 'Toàn website';
    }

    public function run(SeoContext $context): array
    {
        $share = (string) site_config('default_og_image');

        return [
            SeoCheck::tip('search_console', filled(site_config('google_site_verification')), 'Đã khai báo mã Google Search Console.', 'Chưa nhập mã xác minh Search Console trong Cài đặt (bỏ qua nếu đã xác minh bằng DNS).'),
            SeoCheck::tip('analytics', trim(tracking_code('head').tracking_code('body_start').tracking_code('body_end')) !== '', 'Đã gắn mã đo lường.', 'Chưa gắn mã đo lường (Google Analytics) trong Cài đặt.'),
            SeoCheck::tip('default_share_image', $share !== '' && ! str_ends_with(strtolower($share), '.svg'), 'Đã đặt ảnh chia sẻ mặc định.', 'Chưa đặt ảnh chia sẻ mặc định trong Cài đặt.'),
            SeoCheck::tip('favicon', filled(site_config('site_favicon')), 'Đã đặt favicon.', 'Chưa đặt favicon (biểu tượng cạnh kết quả tìm kiếm).'),
        ];
    }
}
