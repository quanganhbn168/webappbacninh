<?php

namespace App\Services\Payment\Contracts;

use App\Services\Payment\DTO\PaymentRequest;
use App\Services\Payment\DTO\PaymentResult;

interface PaymentGatewayInterface
{
    /**
     * Get the provider name (slug).
     */
    public function getName(): string;

    /**
     * Get the provider display name.
     */
    public function getDisplayName(): string;

    /**
     * Get provider logo URL.
     */
    public function getLogo(): string;

    /**
     * Get provider icon class (FontAwesome).
     */
    public function getIcon(): string;

    /**
     * Get provider theme color.
     */
    public function getColor(): string;

    /**
     * Get provider description for checkout.
     */
    public function getDescription(): string;

    /**
     * Create payment URL to redirect user to payment gateway.
     */
    public function createPaymentUrl(PaymentRequest $request): string;

    /**
     * Verify callback/webhook data from payment gateway.
     */
    public function verifyCallback(array $data): PaymentResult;

    /**
     * Check if this provider supports refund.
     */
    public function supportsRefund(): bool;

    /**
     * Process refund for a transaction.
     */
    public function refund(string $transactionId, float $amount, ?string $reason = null): PaymentResult;

    /**
     * Query transaction status from the gateway.
     */
    public function queryTransaction(string $transactionId): PaymentResult;

    /**
     * Get all display info as array (for views).
     */
    public function getDisplayInfo(): array;
}
