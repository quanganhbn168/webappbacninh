<?php

namespace App\Services\Payment\DTO;

use App\Services\Payment\Enums\PaymentStatus;

class PaymentResult
{
    public function __construct(
        public readonly bool $success,
        public readonly PaymentStatus $status,
        public readonly ?string $transactionId = null,
        public readonly ?string $orderId = null,
        public readonly ?float $amount = null,
        public readonly ?string $message = null,
        public readonly ?string $bankCode = null,
        public readonly ?string $paymentMethod = null,
        public readonly array $rawData = [],
    ) {}

    /**
     * Create a successful result.
     */
    public static function success(
        string $transactionId,
        string $orderId,
        float $amount,
        array $rawData = [],
        ?string $message = null,
    ): self {
        return new self(
            success: true,
            status: PaymentStatus::COMPLETED,
            transactionId: $transactionId,
            orderId: $orderId,
            amount: $amount,
            message: $message ?? 'Thanh toán thành công',
            rawData: $rawData,
        );
    }

    /**
     * Create a failed result.
     */
    public static function failed(
        string $message,
        ?string $orderId = null,
        array $rawData = [],
    ): self {
        return new self(
            success: false,
            status: PaymentStatus::FAILED,
            orderId: $orderId,
            message: $message,
            rawData: $rawData,
        );
    }

    /**
     * Create a pending result.
     */
    public static function pending(
        string $orderId,
        ?string $message = null,
        array $rawData = [],
    ): self {
        return new self(
            success: false,
            status: PaymentStatus::PENDING,
            orderId: $orderId,
            message: $message ?? 'Đang chờ thanh toán',
            rawData: $rawData,
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'transaction_id' => $this->transactionId,
            'order_id' => $this->orderId,
            'amount' => $this->amount,
            'message' => $this->message,
            'bank_code' => $this->bankCode,
            'payment_method' => $this->paymentMethod,
        ];
    }
}
