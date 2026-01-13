<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class TiktokService
{
    public function getInfo(string $url): array
    {
        preg_match('/\/video\/(\d+)/', $url, $matches);
        if (!isset($matches[1])) {
            throw new Exception('Không thể trích xuất được Video ID từ link TikTok này.');
        }
        $videoId = $matches[1];
        $playerUrl = "https://www.tiktok.com/player/v1/{$videoId}";

        $oEmbedUrl = 'https://www.tiktok.com/oembed?url=' . urlencode($url);
        $response = Http::get($oEmbedUrl);

        if ($response->failed()) {
            throw new Exception('Không thể kết nối đến TikTok (oEmbed) để lấy thông tin.');
        }

        $videoData = $response->json();

        if (empty($videoData['thumbnail_url'])) {
            throw new Exception('Link TikTok này không hợp lệ hoặc không có thumbnail (oEmbed).');
        }

        return [
            'provider'      => 'tiktok',
            'title'         => $videoData['title'] ?? 'Ảnh từ TikTok',
            'thumbnail_url' => $videoData['thumbnail_url'],
            'url'           => $playerUrl // <-- Đã đổi từ glightbox_url
        ];
    }
}
