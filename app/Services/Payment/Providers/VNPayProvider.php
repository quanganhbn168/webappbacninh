<?php

namespace App\Services\Payment\Providers;

use App\Services\Payment\Contracts\PaymentGatewayInterface;
use App\Services\Payment\DTO\PaymentRequest;
use App\Services\Payment\DTO\PaymentResult;
use App\Services\Payment\Enums\PaymentStatus;

class VNPayProvider implements PaymentGatewayInterface
{
    private string $tmnCode;
    private string $hashSecret;
    private string $vnpUrl;
    private string $returnUrl;

    public function __construct()
    {
        // Read from database settings first, fallback to config/env
        $this->tmnCode = setting('vnpay_tmn_code') ?: config('services.vnpay.tmn_code', '');
        $this->hashSecret = setting('vnpay_hash_secret') ?: config('services.vnpay.hash_secret', '');
        
        $isSandbox = setting('vnpay_sandbox', '1') === '1';
        $this->vnpUrl = $isSandbox
            ? 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'
            : 'https://pay.vnpay.vn/vpcpay.html';
            
        $this->returnUrl = url('/payment/callback/vnpay');
    }

    public function getName(): string
    {
        return 'vnpay';
    }

    public function getDisplayName(): string
    {
        return 'VNPay';
    }

    public function getLogo(): string
    {
        return asset('images/payment/vnpay.png');
    }

    public function getIcon(): string
    {
        return 'fas fa-credit-card';
    }

    public function getColor(): string
    {
        return '#0066b3';
    }

    public function getDescription(): string
    {
        return 'Thẻ ATM, Visa, MasterCard';
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

    public function createPaymentUrl(PaymentRequest $request): string
    {
        $vnpData = [
            'vnp_Version' => '2.1.0',
            'vnp_Command' => 'pay',
            'vnp_TmnCode' => $this->tmnCode,
            'vnp_Amount' => $request->getAmountInSmallestUnit() * 100, // VNPay expects amount * 100
            'vnp_CurrCode' => 'VND',
            'vnp_TxnRef' => $request->orderId,
            'vnp_OrderInfo' => $request->description,
            'vnp_OrderType' => 'billpayment',
            'vnp_Locale' => $request->locale,
            'vnp_ReturnUrl' => $request->returnUrl ?: $this->returnUrl,
            'vnp_IpAddr' => $request->ipAddress ?? request()->ip(),
            'vnp_CreateDate' => date('YmdHis'),
            'vnp_ExpireDate' => date('YmdHis', strtotime('+15 minutes')),
        ];

        // Sort data by key
        ksort($vnpData);

        // Build query string
        $query = http_build_query($vnpData);

        // Create secure hash
        $vnpSecureHash = hash_hmac('sha512', $query, $this->hashSecret);

        // Return full payment URL
        return $this->vnpUrl . '?' . $query . '&vnp_SecureHash=' . $vnpSecureHash;
    }

    public function verifyCallback(array $data): PaymentResult
    {
        // Get and remove the secure hash from data
        $vnpSecureHash = $data['vnp_SecureHash'] ?? '';
        unset($data['vnp_SecureHash'], $data['vnp_SecureHashType']);

        // Sort and rebuild query
        ksort($data);
        $hashData = http_build_query($data);

        // Verify signature
        $calculatedHash = hash_hmac('sha512', $hashData, $this->hashSecret);

        if (!hash_equals($calculatedHash, $vnpSecureHash)) {
            return PaymentResult::failed(
                message: 'Chữ ký không hợp lệ',
                orderId: $data['vnp_TxnRef'] ?? null,
                rawData: $data,
            );
        }

        // Check response code
        $responseCode = $data['vnp_ResponseCode'] ?? '99';
        $transactionStatus = $data['vnp_TransactionStatus'] ?? '99';

        if ($responseCode === '00' && $transactionStatus === '00') {
            return new PaymentResult(
                success: true,
                status: PaymentStatus::COMPLETED,
                transactionId: $data['vnp_TransactionNo'] ?? null,
                orderId: $data['vnp_TxnRef'] ?? null,
                amount: (float) (($data['vnp_Amount'] ?? 0) / 100), // Convert back from VNPay format
                message: 'Thanh toán thành công',
                bankCode: $data['vnp_BankCode'] ?? null,
                paymentMethod: $data['vnp_CardType'] ?? null,
                rawData: $data,
            );
        }

        return PaymentResult::failed(
            message: $this->getErrorMessage($responseCode),
            orderId: $data['vnp_TxnRef'] ?? null,
            rawData: $data,
        );
    }

    public function supportsRefund(): bool
    {
        return true;
    }

    public function refund(string $transactionId, float $amount, ?string $reason = null): PaymentResult
    {
        // VNPay refund requires API call - placeholder for now
        return PaymentResult::failed(
            message: 'Refund chưa được implement',
            orderId: $transactionId,
        );
    }

    public function queryTransaction(string $transactionId): PaymentResult
    {
        // VNPay query requires API call - placeholder for now
        return PaymentResult::pending($transactionId);
    }

    /**
     * Get human-readable error message from VNPay response code.
     */
    private function getErrorMessage(string $code): string
    {
        $messages = [
            '00' => 'Giao dịch thành công',
            '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường)',
            '09' => 'Thẻ/Tài khoản chưa đăng ký dịch vụ InternetBanking',
            '10' => 'Xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
            '11' => 'Đã hết hạn chờ thanh toán',
            '12' => 'Thẻ/Tài khoản bị khóa',
            '13' => 'Nhập sai mật khẩu xác thực giao dịch (OTP)',
            '24' => 'Khách hàng hủy giao dịch',
            '51' => 'Tài khoản không đủ số dư để thực hiện giao dịch',
            '65' => 'Tài khoản đã vượt quá hạn mức giao dịch trong ngày',
            '75' => 'Ngân hàng thanh toán đang bảo trì',
            '79' => 'Nhập sai mật khẩu thanh toán quá số lần quy định',
            '99' => 'Lỗi không xác định',
        ];

        return $messages[$code] ?? 'Lỗi không xác định (mã: ' . $code . ')';
    }
}
