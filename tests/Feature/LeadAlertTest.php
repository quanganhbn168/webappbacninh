<?php

namespace Tests\Feature;

use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LeadAlertTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_lead_is_sent_to_telegram(): void
    {
        config(['services.telegram.bot_token' => 'tok', 'services.telegram.chat_id' => '42']);
        Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);

        $this->postJson('/lien-he', ['name' => 'Khách A', 'phone' => '0900000000', 'need' => 'Website'])->assertCreated();

        Http::assertSent(fn ($r) => str_contains($r->url(), 'bottok/sendMessage')
            && $r['chat_id'] === '42'
            && str_contains($r['text'], 'Khách A')
            && str_contains($r['text'], '0900000000'));
    }

    public function test_telegram_failure_still_keeps_the_lead(): void
    {
        config(['services.telegram.bot_token' => 'tok', 'services.telegram.chat_id' => '42']);
        Http::fake(['api.telegram.org/*' => Http::response([], 500)]);

        $this->postJson('/lien-he', ['name' => 'Khách B', 'phone' => '0900000001'])->assertCreated();

        $this->assertDatabaseHas('leads', ['name' => 'Khách B']);
    }

    public function test_honeypot_drops_bot_submissions_silently(): void
    {
        Http::fake();

        $this->postJson('/lien-he', ['name' => 'Bot', 'phone' => '0900000002', 'website' => 'http://spam'])->assertCreated();

        $this->assertSame(0, Lead::count());
        Http::assertNothingSent();
    }

    public function test_invalid_phone_is_rejected(): void
    {
        $this->postJson('/lien-he', ['name' => 'Khách C', 'phone' => 'abc'])->assertJsonValidationErrors('phone');
    }

    public function test_form_is_rate_limited(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $this->postJson('/lien-he', ['name' => 'Khách', 'phone' => '0900000003'])->assertCreated();
        }

        $this->postJson('/lien-he', ['name' => 'Khách', 'phone' => '0900000003'])->assertStatus(429);
    }
}
