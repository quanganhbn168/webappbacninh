<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');
        foreach ($data as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if (!$setting) continue;

            if ($request->hasFile($key)) {
                // Clear old media
                $setting->clearMediaCollection('settings');
                // Store new media
                $media = $setting->addMediaFromRequest($key)
                    ->preservingOriginal()
                    ->toMediaCollection('settings');
                // Update value with public URL (for current helper compatibility)
                $setting->update(['value' => $media->getUrl()]);
            } else {
                $setting->update(['value' => $value]);
            }
            
            Cache::forget('setting.' . $key);
        }
        
        // Clear global settings cache
        Cache::forget('site_settings');
        
        return back()->with('success', 'Cập nhật cấu hình thành công!');
    }
}
