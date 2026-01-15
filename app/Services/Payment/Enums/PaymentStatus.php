<?php

namespace App\Services\Payment\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Chờ thanh toán',
            self::PROCESSING => 'Đang xử lý',
            self::COMPLETED => 'Thành công',
            self::FAILED => 'Thất bại',
            self::CANCELLED => 'Đã hủy',
            self::REFUNDED => 'Đã hoàn tiền',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::PROCESSING => 'info',
            self::COMPLETED => 'success',
            self::FAILED => 'danger',
            self::CANCELLED => 'secondary',
            self::REFUNDED => 'primary',
        };
    }

    public function isFinished(): bool
    {
        return in_array($this, [self::COMPLETED, self::FAILED, self::CANCELLED, self::REFUNDED]);
    }
}
