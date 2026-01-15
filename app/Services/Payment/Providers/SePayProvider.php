<?php

namespace App\Services\Payment\Providers;

use App\Services\Payment\Contracts\PaymentGatewayInterface;
use App\Services\Payment\DTO\PaymentRequest;
use App\Services\Payment\DTO\PaymentResult;
use App\Services\Payment\Enums\PaymentStatus;

/**
 * SePay Provider - Bank Transfer via QR Code
 * 
 * Flow:
 * 1. User sees QR code with payment info
 * 2. User transfers money via banking app
 * 3. SePay detects transfer → fires webhook
 * 4. Our SePayWebhookListener processes webhook
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
        // Read from database settings first, fallback to config/env
        $this->webhookToken = setting('sepay_webhook_token') ?: config('sepay.webhook_token', '');
        $this->matchPattern = setting('sepay_match_pattern') ?: config('sepay.pattern', 'SE');
        
        // Bank account info for QR generation
        $this->bankName = setting('sepay_bank_name') ?: 'MBBank';
        $this->bankAccount = setting('sepay_bank_account') ?: '';
        $this->accountName = setting('sepay_account_name') ?: 'WebApp Bac Ninh';
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
        // Generate unique payment code with pattern
        $paymentCode = $this->matchPattern . $request->orderId;
        
        // Build VietQR URL for bank transfer
        // Format: https://img.vietqr.io/image/{bankId}-{accountNo}-compact2.png?amount={}&addInfo={}&accountName={}
        $qrParams = http_build_query([
            'amount' => (int) $request->amount,
            'addInfo' => $paymentCode,
            'accountName' => $this->accountName,
        ]);
        
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
     * Verify webhook from SePay.
     * This is called when we receive a webhook from SePay package.
     */
    public function verifyCallback(array $data): PaymentResult
    {
        // For SePay, webhook is handled by the package's SePayWebhookEvent
        // This method is called from our SePayWebhookListener
        
        $orderId = $data['code'] ?? '';
        $transactionId = (string) ($data['id'] ?? '');
        $amount = $data['transferAmount'] ?? 0;
        $transferType = $data['transferType'] ?? '';
        $content = $data['content'] ?? '';
        
        // Remove pattern prefix from code to get order ID
        if (str_starts_with($orderId, $this->matchPattern)) {
            $orderId = substr($orderId, strlen($this->matchPattern));
        }
        
        if ($transferType === 'in') {
            return new PaymentResult(
                success: true,
                status: PaymentStatus::COMPLETED,
                transactionId: $transactionId,
                orderId: $orderId,
                amount: (float) $amount,
                message: 'Thanh toán thành công qua SePay',
                bankCode: $data['gateway'] ?? null,
                paymentMethod: 'Bank Transfer (SePay)',
                rawData: $data,
            );
        }

        return PaymentResult::failed(
            message: 'Giao dịch không hợp lệ',
            orderId: $orderId,
            rawData: $data,
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
        $transaction = \SePay\SePay\Models\SePayTransaction::find($transactionId);
        
        if ($transaction) {
            return PaymentResult::success(
                transactionId: (string) $transaction->id,
                orderId: $transaction->code ?? '',
                amount: (float) $transaction->transfer_amount,
                rawData: $transaction->toArray(),
            );
        }

        return PaymentResult::pending($transactionId);
    }
}
