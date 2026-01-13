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
    public function download(Request $request)
    {
        // Thêm 'url' vào validation để lấy link từ service trả về
        $validator = Validator::make($request->all(), [
            'image_url'    => 'required|url',
            'filename'     => 'nullable|string',
            'provider'     => 'required|string',
            'original_url' => 'required|url',
            'url'          => 'required|url', // Thêm validation cho link nhúng
        ]);

        // Sửa lại view 'anh-cover.blade.php' để thêm input hidden cho 'url'
        // <input type="hidden" name="url" :value="result.url">

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Dữ liệu không hợp lệ, không thể xử lý.');
        }

        try {
            // ----- BƯỚC 1: CHUẨN BỊ DỮ LIỆU -----
            $imageUrl = $request->input('image_url');
            $fileNameFromInput = $request->input('filename');
            $finalFileName = empty(trim($fileNameFromInput))
                ? $request->input('provider') . '-' . time() . '.jpg'
                : Str::slug(Str::limit($fileNameFromInput, 150, '')) . '.jpg';

            // ----- BƯỚC 2: LƯU VÀO SERVER VÀ DATABASE -----
            $imageContents = Http::get($imageUrl)->body();
            $savedPath = 'thumbnails/' . $finalFileName;

            Storage::disk('public')->put($savedPath, $imageContents);

            ThumbnailLog::create([
                'provider'      => $request->input('provider'),
                'original_url'  => $request->input('original_url'),
                'title'         => $fileNameFromInput,
                'thumbnail_url' => $imageUrl,
                'url'           => $request->input('url'), // <-- Lưu vào cột 'url' mới
                'saved_path'    => $savedPath,
            ]);

            // ----- BƯỚC 3: KIỂM TRA CHECKBOX VÀ TRẢ VỀ KẾT QUẢ -----
            if ($request->has('download_to_client')) {
                return response($imageContents)
                    ->header('Content-Type', 'image/jpeg')
                    ->header('Content-Disposition', 'attachment; filename="' . $finalFileName . '"');
            }

            return redirect()->back()->with('success', 'Đã lưu ảnh và thông tin vào server thành công!');

        } catch (Exception $e) {
            Log::error('Lỗi khi download/lưu ảnh: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra trong quá trình xử lý. Vui lòng thử lại.');
        }
    }

    /**
     * Hiển thị danh sách các thumbnail đã lưu.
     */
    public function showList()
    {
        $logs = ThumbnailLog::latest()->paginate(15);
        return view('tools.list-cover', ['logs' => $logs]);
    }

    /**
     * Xóa một log và file ảnh tương ứng.
     */
    public function deleteLog(ThumbnailLog $log)
    {
        try {
            Storage::disk('public')->delete($log->saved_path);
            $log->delete();
            return redirect()->route('cover.list')->with('success', 'Đã xóa thành công!');
        } catch (Exception $e) {
            Log::error('Lỗi khi xóa log: ' . $e->getMessage());
            return redirect()->route('cover.list')->with('error', 'Có lỗi xảy ra, không thể xóa.');
        }
    }


    public function downloadBulk(Request $request)
{
    $validated = $request->validate([
        'items'                 => 'required|array|min:1',
        'items.*.image_url'     => 'required|url',
        'items.*.filename'      => 'nullable|string',
        'items.*.provider'      => 'required|string',
        'items.*.original_url'  => 'required|url',
        'items.*.url'           => 'required|url',
        'download_zip'          => 'nullable|in:true,false,1,0,on,off',
    ], [
        'items.required' => 'Không có mục nào để lưu.',
    ]);

    $items = $validated['items'];
    $downloadZip = filter_var($request->input('download_zip'), FILTER_VALIDATE_BOOLEAN);

    $savedFiles = [];
    $errors = [];

    foreach ($items as $i => $it) {
        try {
            $fileNameFromInput = trim($it['filename'] ?? '');
            // rút gọn để an toàn tên file
            $safeName = \Illuminate\Support\Str::slug(\Illuminate\Support\Str::limit($fileNameFromInput ?: ($it['provider'].'-'.time().'-'.$i), 150, ''), '-');
            $finalFileName = ($safeName ?: 'thumbnail-'.$i).'.jpg';

            $imageContents = Http::get($it['image_url'])->body();
            $savedPath = 'thumbnails/' . $finalFileName;
            Storage::disk('public')->put($savedPath, $imageContents);

            ThumbnailLog::create([
                'provider'      => $it['provider'],
                'original_url'  => $it['original_url'],
                'title'         => $fileNameFromInput ?: null,
                'thumbnail_url' => $it['image_url'],
                'url'           => $it['url'],
                'saved_path'    => $savedPath,
            ]);

            $savedFiles[] = $savedPath;
        } catch (\Throwable $e) {
            Log::error('Bulk save thumbnail error: '.$e->getMessage());
            $errors[] = "Mục #".($i+1)." lỗi: ".$e->getMessage();
        }
    }

    // Nếu không cần tải ZIP: quay lại kèm thông báo
    if (!$downloadZip) {
        $msg = 'Đã lưu '.count($savedFiles).' ảnh vào server.';
        if (!empty($errors)) {
            $msg .= ' Có '.count($errors).' lỗi.';
            return redirect()->back()->with('error', $msg);
        }
        return redirect()->back()->with('success', $msg);
    }

    // Gói ZIP để tải về
    if (empty($savedFiles)) {
        return redirect()->back()->with('error', 'Không có tệp nào để nén.');
    }

    $zipName = 'thumbnails-'.date('Ymd-His').'.zip';
    $zipPath = storage_path('app/public/'.$zipName);

    $zip = new ZipArchive();
    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        return redirect()->back()->with('error', 'Không thể tạo file ZIP.');
    }

    foreach ($savedFiles as $relPath) {
        $abs = Storage::disk('public')->path($relPath);
        if (file_exists($abs)) {
            $zip->addFile($abs, basename($relPath));
        }
    }
    $zip->close();

    return response()->download($zipPath)->deleteFileAfterSend(true);
}
// app/Http/Controllers/ThumbnailController.php
public function showBulkCoverPage()
{
    return view('tools.bulk-anh-cover');
}

}
