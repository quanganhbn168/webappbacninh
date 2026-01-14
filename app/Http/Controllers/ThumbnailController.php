<?php

namespace App\Http\Controllers;

use App\Models\ThumbnailLog;
use App\Services\TiktokService;
use App\Services\YoutubeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use ZipArchive;

class ThumbnailController extends Controller
{
    /**
     * Hiển thị trang công cụ lấy ảnh cover.
     */
    public function showCoverPage()
    {
        return view('tools.anh-cover');
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
     * Tải ảnh, lưu vào server/database và tùy chọn gửi về cho người dùng.
     */
    /**
     * Tải ảnh trực tiếp về máy (Không lưu server).
     */
    public function download(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_url'    => 'required|url',
            'filename'     => 'nullable|string',
            'provider'     => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Dữ liệu không hợp lệ.');
        }

        try {
            $imageUrl = $request->input('image_url');
            $fileNameFromInput = $request->input('filename');
            $finalFileName = empty(trim($fileNameFromInput))
                ? $request->input('provider') . '-' . time() . '.jpg'
                : Str::slug(Str::limit($fileNameFromInput, 150, '')) . '.jpg';

            // Lấy nội dung ảnh
            $imageContents = Http::get($imageUrl)->body();

            // Trả về file stream tải xuống ngay lập tức
            return response($imageContents)
                ->header('Content-Type', 'image/jpeg')
                ->header('Content-Disposition', 'attachment; filename="' . $finalFileName . '"');

        } catch (Exception $e) {
            Log::error('Lỗi download ảnh: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Không thể tải ảnh. Vui lòng thử lại.');
        }
    }

    public function downloadBulk(Request $request)
    {
        $validated = $request->validate([
            'items'                 => 'required|array|min:1',
            'items.*.image_url'     => 'required|url',
            'items.*.filename'      => 'nullable|string',
            'items.*.provider'      => 'required|string',
        ]);

        $items = $validated['items'];

        try {
            $zipName = 'thumbnails-' . date('Ymd-His') . '.zip';
            $zipPath = tempnam(sys_get_temp_dir(), 'zip'); // Tạo file temp

            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                return redirect()->back()->with('error', 'Không thể tạo file ZIP.');
            }

            foreach ($items as $i => $it) {
                try {
                    $fileNameFromInput = trim($it['filename'] ?? '');
                    $safeName = Str::slug(Str::limit($fileNameFromInput ?: ($it['provider'] . '-' . time() . '-' . $i), 150, ''), '-');
                    $finalFileName = ($safeName ?: 'thumbnail-' . $i) . '.jpg';

                    // Tải content ảnh về RAM
                    $imageContents = Http::get($it['image_url'])->body();
                    
                    // Thêm trực tiếp vào ZIP
                    $zip->addFromString($finalFileName, $imageContents);
                } catch (\Throwable $e) {
                    Log::error('Lỗi thêm ảnh vào ZIP: ' . $e->getMessage());
                    // Tiếp tục với ảnh tiếp theo nếu lỗi
                }
            }
            $zip->close();

            // Trả về file ZIP và xóa sau khi gửi
            return response()->download($zipPath, $zipName)->deleteFileAfterSend(true);

        } catch (Exception $e) {
             Log::error('Lỗi bulk download: ' . $e->getMessage());
             return redirect()->back()->with('error', 'Có lỗi xảy ra khi tạo file nén.');
        }
    }

    public function showBulkCoverPage()
    {
        return view('tools.bulk-anh-cover');
    }
}
