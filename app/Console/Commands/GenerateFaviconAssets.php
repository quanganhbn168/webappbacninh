<?php

namespace App\Console\Commands;

use App\Domain\Site\Actions\GenerateFaviconAssets as GenerateFaviconAssetsAction;
use App\Settings\FaviconSettings;
use App\Settings\WebsiteSettings;
use Illuminate\Console\Command;

final class GenerateFaviconAssets extends Command
{
    protected $signature = 'favicon:generate {--force : Tạo lại toàn bộ file dù phiên bản hiện tại đã đủ}';

    protected $description = 'Generate the complete browser, Apple and PWA favicon set from managed settings';

    public function handle(GenerateFaviconAssetsAction $generate): int
    {
        $website = app(WebsiteSettings::class);
        $favicon = app(FaviconSettings::class);

        if (trim($website->site_favicon) === '') {
            $this->error('Chưa có favicon nguồn trong Cài đặt website.');

            return self::FAILURE;
        }

        $result = $generate->execute(
            $website->site_favicon,
            $favicon->maskable_icon,
            $favicon->background_color,
            (bool) $this->option('force'),
        );

        $favicon->generated_version = $result->version;
        $favicon->save();

        $this->info('Đã tạo bộ favicon phiên bản '.$result->version.' tại storage/app/public/'.$result->directory.'.');

        return self::SUCCESS;
    }
}
