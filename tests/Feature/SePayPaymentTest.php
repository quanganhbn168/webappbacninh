<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SePayPaymentTest extends TestCase
{
    use DatabaseTransactions;

    private function webhook(array $overrides = [], ?string $token = 'secret')
    {
        return $this->postJson('/api/sepay/webhook', $overrides + [
            'id' => random_int(1_000_000, 9_999_999),
            'gateway' => 'MBBank',
            'transactionDate' => '2026-11-18 10:00:00',
            'accountNumber' => '0123456789',
            'code' => null,
            'content' => 'CT DEN 123 SEDEMO777 thanh toan',
            'transferType' => 'in',
            'description' => 'test',
            'transferAmount' => 100000,
            'referenceCode' => 'FT1',
            'accumulated' => 0,
        ], $token ? ['Authorization' => 'Apikey '.$token] : []);
    }

    public function test_payment_pages_render_without_sepay_configured(): void
    {
        config(['sepay.webhook_token' => null]);

        $this->get(route('payment.checkout'))->assertOk()->assertSee('SePay');
        $this->get(route('payment.cancel'))->assertOk();
        $this->get(route('payment.result'))->assertOk();
    }

    public function test_webhook_is_refused_without_a_token(): void
    {
        config(['sepay.webhook_token' => null]);

        $this->webhook(token: null)->assertStatus(503);
        $this->assertDatabaseMissing('sepay_transactions', ['content' => 'CT DEN 123 SEDEMO777 thanh toan']);
    }

    public function test_transfer_marks_the_order_paid_and_the_callback_confirms_it(): void
    {
        config(['sepay.webhook_token' => 'secret']);
        $this->withSession(['pending_payment' => ['order_id' => 'DEMO777', 'amount' => 100000, 'provider' => 'sepay']]);

        $this->getJson(route('payment.sepay.status', 'DEMO777'))->assertExactJson(['status' => 'pending']);
        $this->webhook(token: 'wrong')->assertStatus(422);

        $this->webhook()->assertNoContent();

        $this->getJson(route('payment.sepay.status', 'DEMO777'))->assertExactJson(['status' => 'completed']);
        $this->getJson(route('payment.sepay.status', 'DEMO778'))->assertExactJson(['status' => 'pending']);

        $this->get(route('payment.callback', ['provider' => 'sepay', 'order_id' => 'DEMO777']))->assertRedirect(route('payment.result'));
        $this->assertTrue(session('payment_result.success'));
        $this->assertSame(100000.0, (float) session('payment_result.amount'));
    }

    public function test_callback_without_a_transfer_or_with_too_little_money_fails(): void
    {
        config(['sepay.webhook_token' => 'secret']);

        $this->get(route('payment.callback', ['provider' => 'sepay', 'order_id' => 'DEMO777', 'status' => 'success']));
        $this->assertFalse(session('payment_result.success'));

        $this->webhook(['transferAmount' => 5000])->assertNoContent();
        $this->withSession(['pending_payment' => ['order_id' => 'DEMO777', 'amount' => 100000, 'provider' => 'sepay']])
            ->get(route('payment.callback', ['provider' => 'sepay', 'order_id' => 'DEMO777']));
        $this->assertFalse(session('payment_result.success'));
    }

    public function test_qr_page_polls_the_status_route_and_only_returns_to_this_site(): void
    {
        $this->get(route('payment.sepay.qr', ['order_id' => 'DEMO777', 'amount' => 100000, 'return_url' => 'https://evil.example/steal']))
            ->assertOk()
            ->assertSee('data-status-url="'.route('payment.sepay.status', 'DEMO777').'"', false)
            ->assertSee('SEDEMO777')
            ->assertDontSee('evil.example');
    }
}
