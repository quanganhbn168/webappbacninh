<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DuplicateController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'model' => 'required|string',
            'id' => 'required|integer',
            'create_route' => 'required|string', // New required param
        ]);

        $modelClass = $request->input('model');
        // Auto-resolve namespace if missing
        if (!str_contains($modelClass, '\\')) {
            $modelClass = 'App\\Models\\' . ucfirst($modelClass);
        }
        $id = $request->input('id');
        $createRoute = $request->input('create_route');

        // Security check
        if (!class_exists($modelClass)) {
             return back()->with('error', 'Model không hợp lệ.');
        }

        $original = $modelClass::find($id);

        if (!$original) {
            return back()->with('error', 'Dữ liệu gốc không tồn tại.');
        }

        // Prepare data for form filling
        $data = $original->toArray();

        // Append (Copy) to Name/Title
        if (isset($data['name'])) {
            $data['name'] .= ' (Copy)';
        }
        if (isset($data['title'])) {
            $data['title'] .= ' (Copy)';
        }

        // Clear unique fields or system fields
        unset($data['id'], $data['created_at'], $data['updated_at'], $data['deleted_at']);
        $data['slug'] = null; // Clear slug to let user/system regenerate

        // Redirect to the create route with the data as "old" input
        return redirect()->route($createRoute)->withInput($data);
    }
}
