<?php

namespace App\Domain\Site\Actions;

use App\Settings\SocialSettings;
use Illuminate\Support\Facades\Storage;

final class ResolveSocialChannels
{
    /** @return array{footer: array<int, array<string, string>>, floating: array<int, array<string, string>>, wechat: array{id: string, qr_url: string}|null} */
    public function execute(): array
    {
        $settings = app(SocialSettings::class);
        $definitions = [
            'facebook' => ['label' => 'Facebook', 'icon' => 'fa-brands fa-facebook-f', 'floating' => false],
            'messenger' => ['label' => 'Messenger', 'icon' => 'fa-brands fa-facebook-messenger', 'floating' => true],
            'zalo' => ['label' => 'Zalo', 'icon' => 'zalo', 'floating' => true],
            'telegram' => ['label' => 'Telegram', 'icon' => 'fa-brands fa-telegram', 'floating' => true],
            'whatsapp' => ['label' => 'WhatsApp', 'icon' => 'fa-brands fa-whatsapp', 'floating' => true],
            'youtube' => ['label' => 'YouTube', 'icon' => 'fa-brands fa-youtube', 'floating' => false],
        ];
        $footer = [];
        $floating = [];

        foreach ($definitions as $key => $definition) {
            $url = trim((string) $settings->{$key});

            if (! $this->isSecureUrl($url)) {
                continue;
            }

            $channel = [
                'key' => $key,
                'label' => $definition['label'],
                'url' => $url,
                'icon' => $definition['icon'],
            ];
            $footer[] = $channel;

            if ($definition['floating']) {
                $floating[] = $channel;
            }
        }

        $wechatId = trim($settings->wechat_id);
        $wechatQr = ltrim(trim($settings->wechat_qr), '/');
        $wechat = null;

        if ($wechatId !== '' && $wechatQr !== '' && Storage::disk('public')->exists($wechatQr)) {
            $wechat = [
                'id' => $wechatId,
                'qr_url' => app(ResolvePublicAssetUrl::class)->execute($wechatQr),
            ];
        }

        return compact('footer', 'floating', 'wechat');
    }

    private function isSecureUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false
            && strtolower((string) parse_url($url, PHP_URL_SCHEME)) === 'https';
    }
}
