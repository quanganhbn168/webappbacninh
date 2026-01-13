<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TenantRegisterController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'string', 'alpha_num', 'unique:tenants,id'],
            'email' => ['required', 'email'],
        ]);
        
        $tenantId = strtolower($validated['id']);
        
        $tenant = Tenant::create([
            'id' => $tenantId,
            'plan_id' => 'free', // Mặc định gói dùng thử
        ]);

        // Tạo domain: tenantId.webappbacninh.test
        $tenant->domains()->create([
            'domain' => $tenantId . '.' . parse_url(config('app.url'), PHP_URL_HOST),
        ]);

        // Giả lập redirect qua trang quản trị của tenant (sau này sẽ auto login)
        $tenantUrl = 'https://' . $tenantId . '.' . parse_url(config('app.url'), PHP_URL_HOST);

        return redirect()->away($tenantUrl)->with('success', 'Khởi tạo thành công! Chào mừng đến với cửa hàng của bạn.');
    }
}
