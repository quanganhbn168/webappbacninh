<?php

namespace App\Http\Controllers\Auth;

use App\Domain\Identity\Actions\LoginWithSocial;
use App\Domain\Identity\Exceptions\SocialLoginRefused;
use App\Enums\SocialProvider;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class CustomerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ]);
    }

    public function showRegistrationForm()
    {
        return view('frontend.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'accepted',
        ], [
            'terms.accepted' => 'Bạn phải đồng ý với điều khoản và chính sách.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // TODO: Assign 'customer' role if Roles are implemented
        // $user->assignRole('customer');

        Auth::login($user);

        return redirect(route('home'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function redirectToProvider(SocialProvider $provider): RedirectResponse
    {
        abort_unless($provider->isConfigured(), 404);

        return Socialite::driver($provider->value)->redirect();
    }

    public function handleProviderCallback(SocialProvider $provider, LoginWithSocial $login): RedirectResponse
    {
        abort_unless($provider->isConfigured(), 404);

        try {
            $user = $login->execute($provider, Socialite::driver($provider->value)->user());
        } catch (SocialLoginRefused $exception) {
            return redirect()->route('login')->with('error', $exception->getMessage());
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->route('login')->with('error', 'Không thể đăng nhập bằng '.$provider->label().'. Vui lòng thử lại.');
        }

        Auth::login($user, remember: true);
        request()->session()->regenerate();

        return redirect()->intended(route('home'));
    }
}
