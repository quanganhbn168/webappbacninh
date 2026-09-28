<?php

namespace App\Domain\Identity\Actions;

use App\Domain\Identity\Exceptions\SocialLoginRefused;
use App\Enums\SocialProvider;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;

/**
 * Finds or creates the customer account behind a social sign-in.
 *
 * - An account already linked to this provider id signs in directly.
 * - An existing account with the same email is linked only when the
 *   provider verifies email addresses; otherwise the person must sign in
 *   with their password first.
 * - Admin accounts never sign in through a social provider.
 */
final class LoginWithSocial
{
    public function execute(SocialProvider $provider, SocialiteUser $socialUser): User
    {
        $account = SocialAccount::query()->with('user')
            ->where('provider', $provider->value)
            ->where('provider_id', (string) $socialUser->getId())
            ->first();

        if ($account) {
            $this->ensureCustomer($account->user);
            $account->update(['email' => $socialUser->getEmail(), 'avatar' => $socialUser->getAvatar()]);

            return $account->user;
        }

        $email = filled($socialUser->getEmail()) ? Str::lower((string) $socialUser->getEmail()) : null;
        $existing = $email ? User::query()->where('email', $email)->first() : null;

        if ($existing && ! $provider->verifiesEmail()) {
            throw new SocialLoginRefused("Email này đã có tài khoản. Vui lòng đăng nhập bằng mật khẩu, sau đó mới liên kết {$provider->label()}.");
        }

        if ($existing) {
            $this->ensureCustomer($existing);
        }

        return DB::transaction(function () use ($provider, $socialUser, $existing, $email): User {
            $user = $existing ?? User::query()->create([
                'name' => $socialUser->getName() ?: ($socialUser->getNickname() ?: $provider->label().' '.$socialUser->getId()),
                'email' => $email,
                'password' => Str::password(32),
                'avatar' => $socialUser->getAvatar(),
            ]);

            if (! $existing && $email && $provider->verifiesEmail()) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }

            $user->socialAccounts()->create([
                'provider' => $provider->value,
                'provider_id' => (string) $socialUser->getId(),
                'email' => $email,
                'avatar' => $socialUser->getAvatar(),
            ]);

            return $user;
        });
    }

    private function ensureCustomer(User $user): void
    {
        if ($user->roles()->exists()) {
            throw new SocialLoginRefused('Tài khoản quản trị không đăng nhập bằng mạng xã hội. Vui lòng đăng nhập tại trang quản trị.');
        }
    }
}
