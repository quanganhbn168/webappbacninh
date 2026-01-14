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
        
        // 1. Lấy thông tin cơ bản qua oEmbed
        $oEmbedUrl = 'https://www.tiktok.com/oembed?url=' . urlencode($url);
        $response = Http::get($oEmbedUrl);

        if ($response->failed()) {
            throw new Exception('Không thể kết nối đến TikTok (oEmbed) để lấy thông tin.');
        }

        $videoData = $response->json();
        
        // 2. Thử lấy link video (Logic scraping đơn giản - có thể cần update thường xuyên)
        $videoDownloadUrl = $this->getVideoDownloadUrl($videoId);

        if (empty($videoData['thumbnail_url'])) {
            throw new Exception('Link TikTok này không hợp lệ hoặc không có thumbnail (oEmbed).');
        }

        return [
            'provider'      => 'tiktok',
            'title'         => $videoData['title'] ?? 'TikTok Video ' . $videoId,
            'thumbnail_url' => $videoData['thumbnail_url'],
            'url'           => "https://www.tiktok.com/player/v1/{$videoId}",
            'video_url'     => $videoDownloadUrl, // Link tải video
        ];
    }

    private function getVideoDownloadUrl($videoId)
    {
        try {
            // API không chính thức thường dùng để lấy video không logo
            // Lưu ý: API này của bên thứ 3 (tikwm.com hoặc tsk66) thường được dùng trong cộng đồng PHP
            // Vì TikTok đổi algo rất thường xuyên, tự viết regex parse HTML rất dễ hỏng.
            // Ở đây mình sẽ thử dùng một endpoint public API miễn phí để demo cho ổn định.
            
            $response = Http::get("https://tikwm.com/api/?url=https://www.tiktok.com/video/{$videoId}");
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data']['play'])) {
                    return $data['data']['play']; // Link video không logo
                }
            }
        } catch (Exception $e) {
            // log error
        }

        return null;
    }
}
