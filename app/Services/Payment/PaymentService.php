<?php

namespace App\Services\Payment;

use App\Services\Payment\Contracts\PaymentGatewayInterface;
use App\Services\Payment\DTO\PaymentRequest;
use App\Services\Payment\DTO\PaymentResult;
use App\Services\Payment\Providers\VNPayProvider;
use App\Services\Payment\Providers\MoMoProvider;
use App\Services\Payment\Providers\SePayProvider;
use App\Services\Payment\Providers\PayPalProvider;
use InvalidArgumentException;

class PaymentService
{
    /**
     * Registered payment providers.
     */
    private array $providers = [];

    /**
     * Default provider name.
     */
    private string $defaultProvider;

    public function __construct()
    {
        $this->defaultProvider = config('services.payment.default', 'vnpay');
        $this->registerDefaultProviders();
    }

    /**
     * Register default payment providers.
     */
    private function registerDefaultProviders(): void
    {
        $this->register('vnpay', new VNPayProvider());
        $this->register('momo', new MoMoProvider());
        $this->register('sepay', new SePayProvider());
        $this->register('paypal', new PayPalProvider());
    }

    /**
     * Register a payment provider.
     */
    public function register(string $name, PaymentGatewayInterface $provider): self
    {
        $this->providers[$name] = $provider;
        return $this;
    }

    /**
     * Get a provider by name.
     */
    public function provider(?string $name = null): PaymentGatewayInterface
    {
        $name = $name ?? $this->defaultProvider;

        if (!isset($this->providers[$name])) {
            throw new InvalidArgumentException("Payment provider [{$name}] not found.");
        }

        return $this->providers[$name];
    }

    /**
     * Get all registered providers.
     */
    public function getProviders(): array
    {
        return $this->providers;
    }

    /**
     * Get available providers for display (e.g., in checkout page).
     */
    public function getAvailableProviders(): array
    {
        $available = [];

        foreach ($this->providers as $name => $provider) {
            $available[] = $provider->getDisplayInfo();
        }

        return $available;
    }

    /**
     * Create payment URL using specified provider.
     */
    public function createPaymentUrl(PaymentRequest $request, ?string $provider = null): string
    {
        return $this->provider($provider)->createPaymentUrl($request);
    }

    /**
     * Verify callback from a provider.
     */
    public function verifyCallback(array $data, string $provider): PaymentResult
    {
        return $this->provider($provider)->verifyCallback($data);
    }

    /**
     * Process refund.
     */
    public function refund(string $transactionId, float $amount, string $provider, ?string $reason = null): PaymentResult
    {
        $gateway = $this->provider($provider);

        if (!$gateway->supportsRefund()) {
            return PaymentResult::failed("Provider [{$provider}] không hỗ trợ hoàn tiền.");
        }

        return $gateway->refund($transactionId, $amount, $reason);
    }

    /**
     * Check if a provider is available.
     */
    public function hasProvider(string $name): bool
    {
        return isset($this->providers[$name]);
    }

    /**
     * Get default provider name.
     */
    public function getDefaultProvider(): string
    {
        return $this->defaultProvider;
    }

    /**
     * Set default provider.
     */
    public function setDefaultProvider(string $name): self
    {
        if (!$this->hasProvider($name)) {
            throw new InvalidArgumentException("Payment provider [{$name}] not found.");
        }

        $this->defaultProvider = $name;
        return $this;
    }
}
