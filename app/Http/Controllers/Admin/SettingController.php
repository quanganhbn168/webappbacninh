<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

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
        
        // Image fields that should be uploaded
        $imageFields = ['site_logo_wide', 'site_logo_white', 'site_logo_square', 'site_favicon'];
        
        foreach ($data as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if (!$setting) continue;

            // Handle file uploads for image fields
            if (in_array($key, $imageFields) && $request->hasFile($key)) {
                $file = $request->file($key);
                $filename = $key . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/settings'), $filename);
                $value = 'images/settings/' . $filename;
            }

            // Save value (either uploaded path or text value)
            $setting->update(['value' => $value ?? '']);
            Cache::forget('setting.' . $key);
        }
        
        // Clear global settings cache
        Cache::forget('site_settings');
        
        return back()->with('success', 'Cập nhật cấu hình thành công!');
    }

    public function sendTestMail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            Mail::raw('Đây là email kiểm tra kết nối SMTP từ WebApp Bắc Ninh.', function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Test SMTP Connection - WebApp Bắc Ninh');
            });

            return back()->with('success', 'Gửi email test thành công! Hãy kiểm tra hộp thư đến (hoặc spam).');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi gửi mail: ' . $e->getMessage());
        }
    }
}
