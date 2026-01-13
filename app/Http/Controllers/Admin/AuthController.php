<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->hasRole('super_admin')) {
            return redirect()->route('admin.dashboard');
        }
        return view('vendor.adminlte.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->hasRole('super_admin')) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }
            
            Auth::logout();
            return back()->withErrors([
                'email' => 'Tài khoản không có quyền truy cập quản trị.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
