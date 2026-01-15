<?php

namespace App\Services\Payment\DTO;

class PaymentRequest
{
    public function __construct(
        public readonly string $orderId,
        public readonly float $amount,
        public readonly string $description,
        public readonly string $returnUrl,
        public readonly ?string $cancelUrl = null,
        public readonly ?string $customerEmail = null,
        public readonly ?string $customerPhone = null,
        public readonly ?string $customerName = null,
        public readonly ?string $ipAddress = null,
        public readonly string $locale = 'vn',
        public readonly string $currency = 'VND',
        public readonly array $metadata = [],
    ) {}

    /**
     * Create from array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            orderId: $data['order_id'] ?? uniqid('ORDER_'),
            amount: (float) ($data['amount'] ?? 0),
            description: $data['description'] ?? 'Thanh toán đơn hàng',
            returnUrl: $data['return_url'] ?? '',
            cancelUrl: $data['cancel_url'] ?? null,
            customerEmail: $data['customer_email'] ?? null,
            customerPhone: $data['customer_phone'] ?? null,
            customerName: $data['customer_name'] ?? null,
            ipAddress: $data['ip_address'] ?? request()->ip(),
            locale: $data['locale'] ?? 'vn',
            currency: $data['currency'] ?? 'VND',
            metadata: $data['metadata'] ?? [],
        );
    }

    /**
     * Get amount in smallest currency unit (for VND, this is the same).
     */
    public function getAmountInSmallestUnit(): int
    {
        return (int) $this->amount;
    }
}
