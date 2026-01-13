<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Tenant;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with('domains')->latest()->paginate(10);
        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('admin.tenants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|unique:tenants,id',
            'domain' => 'required|unique:domains,domain',
        ]);

        $tenant = Tenant::create(['id' => $request->id]);
        $tenant->domains()->create(['domain' => $request->domain . '.' . config('tenancy.central_domains')[0]]);

        return redirect()->route('admin.tenants.index')->with('success', 'Tạo tenant thành công!');
    }
}
