<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class YoutubeService
{
    public function getInfo(string $url): array
    {
        $videoId = $this->extractVideoId($url);
        $originalUrl = 'https://www.youtube.com/watch?v=' . $videoId;

        $oEmbedUrl = 'https://www.youtube.com/oembed?url=' . urlencode($originalUrl) . '&format=json';
        $response = Http::get($oEmbedUrl);

        if ($response->failed()) {
            throw new Exception('Không thể lấy tiêu đề từ YouTube.');
        }

        $videoData = $response->json();
        $title = $videoData['title'] ?? 'Ảnh từ YouTube';

        $bestThumbnail = $this->findBestAvailableThumbnail($videoId);

        return [
            'provider'      => 'youtube',
            'title'         => $title,
            'thumbnail_url' => $bestThumbnail,
            'url'           => $originalUrl // <-- Đã đổi từ glightbox_url
        ];
    }

    private function extractVideoId(string $url): string
    {
        preg_match('/(v=|vi=|youtu.be\/|embed\/|shorts\/)([a-zA-Z0-9_-]{11})/', $url, $matches);
        if (!isset($matches[2])) {
            throw new Exception('Không tìm thấy YouTube Video ID trong link.');
        }
        return $matches[2];
    }

    private function findBestAvailableThumbnail(string $videoId): string
    {
        $qualities = [
            'maxresdefault.jpg',
            'sddefault.jpg',
            'hqdefault.jpg',
            'mqdefault.jpg',
            'default.jpg'
        ];

        $baseUrl = "https://img.youtube.com/vi/{$videoId}/";

        foreach ($qualities as $quality) {
            $url = $baseUrl . $quality;
            $response = Http::head($url);

            if ($response->successful()) {
                return $url;
            }
        }

        return $baseUrl . 'default.jpg';
    }
}
