<?php

namespace Tests\Feature;

use App\Domain\Identity\Actions\LoginWithSocial;
use App\Domain\Identity\Exceptions\SocialLoginRefused;
use App\Enums\SocialProvider;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\TestCase;

class SocialLoginTest extends TestCase
{
    use DatabaseTransactions;

    public function test_first_sign_in_creates_a_customer_and_the_next_one_reuses_it(): void
    {
        $id = (string) Str::uuid();
        $email = 'khach-'.Str::lower(Str::random(8)).'@example.com';

        $user = app(LoginWithSocial::class)->execute(SocialProvider::Google, $this->socialUser($id, $email));
        $again = app(LoginWithSocial::class)->execute(SocialProvider::Google, $this->socialUser($id, $email));

        $this->assertSame($user->id, $again->id);
        $this->assertNotNull($user->email_verified_at);
        $this->assertDatabaseHas('social_accounts', ['user_id' => $user->id, 'provider' => 'google', 'provider_id' => $id]);
    }

    public function test_google_links_to_an_existing_customer_with_the_same_verified_email(): void
    {
        $customer = User::factory()->create(['email' => 'lienket-'.Str::lower(Str::random(8)).'@example.com']);

        $user = app(LoginWithSocial::class)->execute(SocialProvider::Google, $this->socialUser((string) Str::uuid(), strtoupper($customer->email)));

        $this->assertSame($customer->id, $user->id);
    }

    public function test_providers_without_verified_email_cannot_take_over_an_existing_account(): void
    {
        $customer = User::factory()->create(['email' => 'nannhan-'.Str::lower(Str::random(8)).'@example.com']);

        $this->expectException(SocialLoginRefused::class);

        app(LoginWithSocial::class)->execute(SocialProvider::Facebook, $this->socialUser((string) Str::uuid(), $customer->email));
    }

    public function test_admin_accounts_never_sign_in_through_social_providers(): void
    {
        $admin = User::query()->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();

        $this->expectException(SocialLoginRefused::class);

        app(LoginWithSocial::class)->execute(SocialProvider::Google, $this->socialUser((string) Str::uuid(), $admin->email));
    }

    public function test_zalo_users_without_email_get_an_account(): void
    {
        $user = app(LoginWithSocial::class)->execute(SocialProvider::Zalo, $this->socialUser('zalo-'.Str::random(10), null, 'Nguyễn Văn Zalo'));

        $this->assertNull($user->email);
        $this->assertSame('Nguyễn Văn Zalo', $user->name);
    }

    public function test_unknown_or_unconfigured_providers_are_not_found(): void
    {
        config(['services.google.client_id' => null, 'services.google.client_secret' => null]);

        $this->get('/auth/github')->assertNotFound();
        $this->get('/auth/google')->assertNotFound();
        $this->get(route('login'))->assertOk()->assertDontSee('/auth/google');
    }

    public function test_configured_providers_show_their_button(): void
    {
        config(['services.facebook.client_id' => 'id', 'services.facebook.client_secret' => 'secret']);

        $this->get(route('login'))->assertOk()->assertSee(route('social.login', 'facebook'), false);
    }

    private function socialUser(string $id, ?string $email, string $name = 'Khách hàng'): SocialiteUser
    {
        return (new SocialiteUser)->map(['id' => $id, 'email' => $email, 'name' => $name, 'nickname' => null, 'avatar' => null]);
    }
}
