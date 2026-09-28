<?php

namespace Tests\Feature;

use App\Models\Tenant;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Tests\TestCase;

class TenantRegistrationTest extends TestCase
{
    public function test_guests_cannot_create_tenants(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class)
            ->post('/create-tenant', ['id' => 'khachthu', 'email' => 'khach@example.com'])
            ->assertRedirect();

        $this->assertFalse(Tenant::query()->whereKey('khachthu')->exists());
    }
}
