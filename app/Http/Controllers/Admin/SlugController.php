<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Slug;

class SlugController extends Controller
{
    /**
     * Check slug uniqueness via AJAX.
     * This is a global check against the slugs table.
     */
    public function check(Request $request)
    {
        $slugKey = Str::slug($request->slug);
        
        // Check in global slugs table
        // We check if the slug key exists.
        // If 'exclude_id' and 'model' (reference_type) are provided, we allow the slug if it belongs to THAT specific entity.
        
        $query = Slug::where('key', $slugKey);
        
        if ($request->has('exclude_id') && $request->has('model')) {
            $excludeId = $request->exclude_id;
            $modelInput = $request->model;
            // Auto-prepend namespace if missing - standardized with BulkActionController
            $modelClass = str_contains($modelInput, '\\') ? $modelInput : 'App\\Models\\' . $modelInput;
            
            // If the slug exists, check if it belongs to the current entity being edited.
            // Logic: The slug is "taken" if it exists AND (it belongs to a different ID OR different Type)
            
            // Simpler: Check if there is any record with this key that is NOT the current entity
            $query->where(function ($q) use ($excludeId, $modelClass) {
                $q->where('reference_id', '!=', $excludeId)
                  ->orWhere('reference_type', '!=', $modelClass);
            });
        }

        $exists = $query->exists();
        
        return response()->json([
            'exists' => $exists,
            'slug' => $slugKey,
            'message' => $exists ? 'Đường dẫn này đã được sử dụng, vui lòng chọn đường dẫn khác.' : 'Đường dẫn hợp lệ!'
        ]);
    }
}
