<?php

namespace App\Http\Controllers;

use App\Domain\Tools\Actions\FetchRemoteMedia;
use App\Domain\Tools\Actions\ZipThumbnails;
use App\Domain\Tools\Exceptions\RemoteMediaRefused;
use App\Http\Controllers\Frontend\FrontendController;
use App\Services\TiktokService;
use App\Services\YoutubeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ThumbnailController extends FrontendController
{
    /**
     * Hiển thị trang công cụ lấy ảnh cover.
     */
    public function showCoverPage()
    {
        return $this->toolPage('anh-cover');
    }

    /**
     * Phân tích URL và trả về thông tin thumbnail.
     */
    public function getInfo(Request $request, YoutubeService $youtubeService, TiktokService $tiktokService)
    {
        $validator = Validator::make($request->all(), ['url' => 'required|url']);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Đường dẫn không hợp lệ.'], 400);
        }

        $url = $request->input('url');

        try {
            if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
                $data = $youtubeService->getInfo($url);
            } elseif (str_contains($url, 'tiktok.com')) {
                $data = $tiktokService->getInfo($url);
            } else {
                throw new Exception('Chỉ hỗ trợ link từ YouTube và TikTok.');
            }

            return response()->json(['success' => true] + $data);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Tải một ảnh cover về máy (không lưu trên server).
     */
    public function download(Request $request, FetchRemoteMedia $fetch)
    {
        $data = $request->validate([
            'image_url' => 'required|url|max:2048',
            'filename' => 'nullable|string|max:255',
            'provider' => 'required|string|max:20',
        ]);

        try {
            $image = $fetch->read($data['image_url']);
        } catch (RemoteMediaRefused $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return response($image)
            ->header('Content-Type', 'image/jpeg')
            ->header('Content-Disposition', 'attachment; filename="'.$this->fileName($data['filename'] ?? null, $data['provider'], 'jpg').'"');
    }

    public function downloadBulk(Request $request, ZipThumbnails $zipThumbnails)
    {
        $data = $request->validate([
            'items' => 'required|array|min:1|max:'.ZipThumbnails::MAX_ITEMS,
            'items.*.image_url' => 'required|url|max:2048',
            'items.*.filename' => 'nullable|string|max:255',
            'items.*.provider' => 'required|string|max:20',
        ]);

        try {
            $path = $zipThumbnails->execute($data['items']);
        } catch (RemoteMediaRefused $exception) {
            return back()->with('error', $exception->getMessage());
        }

        if ($path === null) {
            return back()->with('error', 'Không tải được ảnh nào trong danh sách.');
        }

        return response()->download($path, 'thumbnails-'.date('Ymd-His').'.zip')->deleteFileAfterSend();
    }

    /**
     * Tải video TikTok về máy, chuyển tiếp từng phần (không giữ cả file trong RAM).
     */
    public function downloadVideo(Request $request, FetchRemoteMedia $fetch)
    {
        $data = $request->validate([
            'video_url' => 'required|url|max:2048',
            'filename' => 'nullable|string|max:255',
        ]);

        try {
            $stream = $fetch->open($data['video_url'], FetchRemoteMedia::VIDEO_MAX_BYTES);
        } catch (RemoteMediaRefused $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return response()->streamDownload(
            fn () => FetchRemoteMedia::copyTo($stream, FetchRemoteMedia::VIDEO_MAX_BYTES, function (string $chunk): void {
                echo $chunk;
                flush();
            }),
            $this->fileName($data['filename'] ?? null, 'video', 'mp4'),
            ['Content-Type' => 'video/mp4'],
        );
    }

    private function fileName(?string $wanted, string $fallback, string $extension): string
    {
        $name = Str::slug(Str::limit(trim((string) $wanted), 150, ''));

        return ($name ?: Str::slug($fallback).'-'.time()).'.'.$extension;
    }

    public function showBulkCoverPage()
    {
        return $this->toolPage('bulk-anh-cover');
    }
}
