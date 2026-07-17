<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BulkActionController extends Controller
{
    public function destroy(Request $request)
    {
        $request->validate([
            'ids' => 'required',
            'model' => 'required|string',
        ]);

        $modelInput = $request->input('model');
        // Auto-prepend namespace if missing
        $modelClass = str_contains($modelInput, '\\') ? $modelInput : 'App\\Models\\' . $modelInput;
        
        // Security check: Ensure class exists
        if (!class_exists($modelClass)) {
             return back()->with('error', 'Model không hợp lệ: ' . $modelInput);
        }

        $ids = json_decode($request->input('ids'), true);
        
        if (empty($ids)) {
            return back()->with('error', 'Chưa chọn mục nào để xóa.');
        }

        try {
            // Use the model's destroy method which handles events/observers
            $count = $modelClass::destroy($ids);
            return back()->with('success', "Đã xóa {$count} mục thành công!");
        } catch (\Exception $e) {
            return back()->with('error', 'Đã xảy ra lỗi khi xóa: ' . $e->getMessage());
        }
    }
}
