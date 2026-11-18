<?php

namespace App\Services\Payment\Providers;

use App\Services\Payment\Contracts\PaymentGatewayInterface;
use App\Services\Payment\DTO\PaymentRequest;
use App\Services\Payment\DTO\PaymentResult;
use App\Services\Payment\Enums\PaymentStatus;
use Illuminate\Support\Facades\Schema;
use SePay\SePay\Models\SePayTransaction;

/**
 * SePay Provider - Bank Transfer via QR Code
 * 
 * Flow:
 * 1. User sees QR code with payment info
 * 2. User transfers money via banking app
 * 3. SePay detects transfer → fires webhook
 * 4. The package stores it in sepay_transactions; the QR page polls
 *    payment.sepay.status, then payment/callback/sepay confirms it again here
 * 
 * Uses official package: sepayvn/laravel-sepay
 */
class SePayProvider implements PaymentGatewayInterface
{
    private string $webhookToken;
    private string $matchPattern;
    private string $bankName;
    private string $bankAccount;
    private string $accountName;

    public function __construct()
    {
        // Settings first, then config/env. Cast to string: config() returns null, not the
        // default, when SEPAY_WEBHOOK_TOKEN is missing from .env.
        $this->webhookToken = (string) (setting('sepay_webhook_token') ?: config('sepay.webhook_token'));
        $this->matchPattern = (string) (setting('sepay_match_pattern') ?: config('sepay.pattern') ?: 'SE');

        // Bank account info for QR generation
        $this->bankName = (string) (setting('sepay_bank_name') ?: 'MBBank');
        $this->bankAccount = (string) (setting('sepay_bank_account') ?: '');
        $this->accountName = (string) (setting('sepay_account_name') ?: 'WebApp Bac Ninh');
    }

    /**
     * The transfer content the customer must type: pattern + order id, e.g. SEDEMO1731900000.
     */
    public function paymentCode(string $orderId): string
    {
        return $this->matchPattern.$orderId;
    }

    /**
     * The incoming transfer recorded by the SePay webhook for this order, if any.
     * Banks may drop punctuation from the content, so the code is matched on letters and digits only.
     */
    public function findIncomingTransfer(string $orderId, int $minAmount = 0): ?SePayTransaction
    {
        $code = $this->paymentCode($orderId);
        $needle = strtoupper((string) preg_replace('/[^A-Za-z0-9]/', '', $code));
        if ($orderId === '' || $needle === '' || ! Schema::hasTable('sepay_transactions')) {
            return null;
        }

        return SePayTransaction::query()
            ->where('transferType', 'in')
            ->when($minAmount > 0, fn ($query) => $query->where('transferAmount', '>=', $minAmount))
            ->latest('id')
            ->limit(200)
            ->get()
            ->first(fn (SePayTransaction $transfer): bool => $transfer->code === $code
                || str_contains(strtoupper((string) preg_replace('/[^A-Za-z0-9]/', '', $transfer->content)), $needle));
    }

    public function getName(): string
    {
        return 'sepay';
    }

    public function getDisplayName(): string
    {
        return 'SePay';
    }

    public function getLogo(): string
    {
        return 'https://sepay.vn/assets/img/logo/sepay-blue-154x50.png';
    }

    public function getIcon(): string
    {
        return 'fas fa-qrcode';
    }

    public function getColor(): string
    {
        return '#00a651';
    }

    public function getDescription(): string
    {
        return 'Chuyển khoản ngân hàng QR';
    }

    public function getDisplayInfo(): array
    {
        return [
            'name' => $this->getName(),
            'display_name' => $this->getDisplayName(),
            'logo' => $this->getLogo(),
            'icon' => $this->getIcon(),
            'color' => $this->getColor(),
            'description' => $this->getDescription(),
        ];
    }

    /**
     * For SePay, we don't redirect to a payment gateway.
     * Instead, we show a QR code page where user can scan and transfer.
     * The "payment URL" is our own QR display page.
     */
    public function createPaymentUrl(PaymentRequest $request): string
    {
        $paymentCode = $this->paymentCode($request->orderId);

        // Return our internal payment page with QR info
        return route('payment.sepay.qr', [
            'order_id' => $request->orderId,
            'amount' => $request->amount,
            'code' => $paymentCode,
            'bank' => $this->bankName,
            'account' => $this->bankAccount,
            'name' => $this->accountName,
            'return_url' => $request->returnUrl,
        ]);
    }

    /**
     * Called when the QR page sends the customer back (payment/callback/sepay?order_id=…).
     * The redirect itself proves nothing; payment is confirmed only by a transfer the
     * SePay webhook stored for this order (at least the expected amount, when known).
     */
    public function verifyCallback(array $data): PaymentResult
    {
        $orderId = (string) ($data['order_id'] ?? '');
        $transfer = $this->findIncomingTransfer($orderId, (int) ($data['expected_amount'] ?? 0));

        if (! $transfer) {
            return PaymentResult::failed(
                message: 'Chưa nhận được chuyển khoản cho đơn hàng này.',
                orderId: $orderId,
                rawData: $data,
            );
        }

        return new PaymentResult(
            success: true,
            status: PaymentStatus::COMPLETED,
            transactionId: (string) $transfer->id,
            orderId: $orderId,
            amount: (float) $transfer->transferAmount,
            message: 'Thanh toán thành công qua SePay',
            bankCode: $transfer->gateway,
            paymentMethod: 'Bank Transfer (SePay)',
            rawData: $transfer->toArray(),
        );
    }

    public function supportsRefund(): bool
    {
        return false; // Bank transfer cannot auto-refund
    }

    public function refund(string $transactionId, float $amount, ?string $reason = null): PaymentResult
    {
        return PaymentResult::failed('SePay không hỗ trợ hoàn tiền tự động. Vui lòng hoàn tiền thủ công.');
    }

    public function queryTransaction(string $transactionId): PaymentResult
    {
        // SePay stores transactions in sepay_transactions table via package
        $transaction = SePayTransaction::find($transactionId);
        
        if ($transaction) {
            return PaymentResult::success(
                transactionId: (string) $transaction->id,
                orderId: $transaction->code ?? '',
                amount: (float) $transaction->transferAmount,
                rawData: $transaction->toArray(),
            );
        }

        return PaymentResult::pending($transactionId);
    }
}
