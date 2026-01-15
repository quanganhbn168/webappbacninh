<?php

namespace App\Services\Payment\Providers;

use App\Services\Payment\Contracts\PaymentGatewayInterface;
use App\Services\Payment\DTO\PaymentRequest;
use App\Services\Payment\DTO\PaymentResult;
use App\Services\Payment\Enums\PaymentStatus;

class MoMoProvider implements PaymentGatewayInterface
{
    private string $partnerCode;
    private string $accessKey;
    private string $secretKey;
    private string $endpoint;

    public function __construct()
    {
        // Read from database settings first, fallback to config/env
        $this->partnerCode = setting('momo_partner_code') ?: config('services.momo.partner_code', '');
        $this->accessKey = setting('momo_access_key') ?: config('services.momo.access_key', '');
        $this->secretKey = setting('momo_secret_key') ?: config('services.momo.secret_key', '');
        $this->endpoint = 'https://test-payment.momo.vn/v2/gateway/api/create';
    }

    public function getName(): string
    {
        return 'momo';
    }

    public function getDisplayName(): string
    {
        return 'MoMo';
    }

    public function getLogo(): string
    {
        return asset('images/payment/momo.png');
    }

    public function getIcon(): string
    {
        return 'fas fa-mobile-alt';
    }

    public function getColor(): string
    {
        return '#ae2070';
    }

    public function getDescription(): string
    {
        return 'Ví MoMo, QR Code';
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
        $requestId = time() . '';
        $orderId = $request->orderId;
        $amount = (int) $request->amount;
        $orderInfo = $request->description;
        $redirectUrl = $request->returnUrl;
        $ipnUrl = $request->cancelUrl ?? $request->returnUrl;
        $extraData = base64_encode(json_encode($request->metadata));
        $requestType = 'payWithMethod';

        // Create signature
        $rawHash = "accessKey={$this->accessKey}&amount={$amount}&extraData={$extraData}&ipnUrl={$ipnUrl}&orderId={$orderId}&orderInfo={$orderInfo}&partnerCode={$this->partnerCode}&redirectUrl={$redirectUrl}&requestId={$requestId}&requestType={$requestType}";
        $signature = hash_hmac('sha256', $rawHash, $this->secretKey);

        $data = [
            'partnerCode' => $this->partnerCode,
            'partnerName' => 'WebApp Bắc Ninh',
            'storeId' => $this->partnerCode,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature,
        ];

        // Call MoMo API
        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($data)),
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        return $result['payUrl'] ?? '';
    }

    public function verifyCallback(array $data): PaymentResult
    {
        $orderId = $data['orderId'] ?? '';
        $amount = $data['amount'] ?? 0;
        $requestId = $data['requestId'] ?? '';
        $resultCode = $data['resultCode'] ?? -1;
        $transId = $data['transId'] ?? '';
        $message = $data['message'] ?? '';
        $signature = $data['signature'] ?? '';
        $extraData = $data['extraData'] ?? '';

        // Verify signature
        $rawHash = "accessKey={$this->accessKey}&amount={$amount}&extraData={$extraData}&message={$message}&orderId={$orderId}&orderInfo={$data['orderInfo']}&orderType={$data['orderType']}&partnerCode={$this->partnerCode}&payType={$data['payType']}&requestId={$requestId}&responseTime={$data['responseTime']}&resultCode={$resultCode}&transId={$transId}";
        $calculatedSignature = hash_hmac('sha256', $rawHash, $this->secretKey);

        if (!hash_equals($calculatedSignature, $signature)) {
            return PaymentResult::failed(
                message: 'Chữ ký không hợp lệ',
                orderId: $orderId,
                rawData: $data,
            );
        }

        if ($resultCode == 0) {
            return new PaymentResult(
                success: true,
                status: PaymentStatus::COMPLETED,
                transactionId: $transId,
                orderId: $orderId,
                amount: (float) $amount,
                message: 'Thanh toán thành công',
                paymentMethod: 'MoMo',
                rawData: $data,
            );
        }

        return PaymentResult::failed(
            message: $message ?: 'Thanh toán thất bại',
            orderId: $orderId,
            rawData: $data,
        );
    }

    public function supportsRefund(): bool
    {
        return true;
    }

    public function refund(string $transactionId, float $amount, ?string $reason = null): PaymentResult
    {
        return PaymentResult::failed('Refund chưa được implement');
    }

    public function queryTransaction(string $transactionId): PaymentResult
    {
        return PaymentResult::pending($transactionId);
    }
}
