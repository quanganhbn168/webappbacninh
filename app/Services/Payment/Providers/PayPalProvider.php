<?php

namespace App\Services\Payment\Providers;

use App\Services\Payment\Contracts\PaymentGatewayInterface;
use App\Services\Payment\DTO\PaymentRequest;
use App\Services\Payment\DTO\PaymentResult;
use App\Services\Payment\Enums\PaymentStatus;

class PayPalProvider implements PaymentGatewayInterface
{
    private string $clientId;
    private string $clientSecret;
    private string $mode; // 'sandbox' or 'live'
    private string $baseUrl;

    public function __construct()
    {
        // Read from database settings first, fallback to config/env
        $this->clientId = setting('paypal_client_id') ?: config('services.paypal.client_id', '');
        $this->clientSecret = setting('paypal_client_secret') ?: config('services.paypal.client_secret', '');
        
        $isSandbox = setting('paypal_sandbox', '1') === '1';
        $this->mode = $isSandbox ? 'sandbox' : 'live';
        $this->baseUrl = $isSandbox 
            ? 'https://api-m.sandbox.paypal.com' 
            : 'https://api-m.paypal.com';
    }

    public function getName(): string
    {
        return 'paypal';
    }

    public function getDisplayName(): string
    {
        return 'PayPal';
    }

    public function getLogo(): string
    {
        return asset('images/payment/paypal.png');
    }

    public function getIcon(): string
    {
        return 'fab fa-paypal';
    }

    public function getColor(): string
    {
        return '#003087';
    }

    public function getDescription(): string
    {
        return 'Thanh toán quốc tế';
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
        // Get access token
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return '';
        }

        // Convert VND to USD (PayPal doesn't support VND directly)
        $amountUsd = $request->currency === 'VND' 
            ? round($request->amount / 25000, 2) // Approximate rate
            : $request->amount;

        // Create order
        $orderData = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => $request->orderId,
                    'description' => $request->description,
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => number_format($amountUsd, 2, '.', ''),
                    ],
                ],
            ],
            'application_context' => [
                'brand_name' => 'WebApp Bắc Ninh',
                'locale' => 'vi-VN',
                'landing_page' => 'LOGIN',
                'user_action' => 'PAY_NOW',
                'return_url' => $request->returnUrl,
                'cancel_url' => $request->cancelUrl ?? $request->returnUrl . '?cancelled=1',
            ],
        ];

        $ch = curl_init($this->baseUrl . '/v2/checkout/orders');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($orderData),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken,
            ],
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        // Find approval link
        foreach ($result['links'] ?? [] as $link) {
            if ($link['rel'] === 'approve') {
                return $link['href'];
            }
        }

        return '';
    }

    public function verifyCallback(array $data): PaymentResult
    {
        $token = $data['token'] ?? '';
        $payerId = $data['PayerID'] ?? '';

        if (empty($token) || empty($payerId)) {
            return PaymentResult::failed(
                message: 'Thiếu thông tin giao dịch',
                rawData: $data,
            );
        }

        // Capture the order
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return PaymentResult::failed('Không thể xác thực với PayPal');
        }

        $ch = curl_init($this->baseUrl . '/v2/checkout/orders/' . $token . '/capture');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => '{}',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken,
            ],
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (($result['status'] ?? '') === 'COMPLETED') {
            $purchaseUnit = $result['purchase_units'][0] ?? [];
            $capture = $purchaseUnit['payments']['captures'][0] ?? [];

            return new PaymentResult(
                success: true,
                status: PaymentStatus::COMPLETED,
                transactionId: $capture['id'] ?? $token,
                orderId: $purchaseUnit['reference_id'] ?? '',
                amount: (float) ($capture['amount']['value'] ?? 0) * 25000, // Convert back to VND
                message: 'Thanh toán PayPal thành công',
                paymentMethod: 'PayPal',
                rawData: $result,
            );
        }

        return PaymentResult::failed(
            message: $result['message'] ?? 'Thanh toán PayPal thất bại',
            orderId: $token,
            rawData: $result,
        );
    }

    public function supportsRefund(): bool
    {
        return true;
    }

    public function refund(string $transactionId, float $amount, ?string $reason = null): PaymentResult
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return PaymentResult::failed('Không thể xác thực với PayPal');
        }

        $amountUsd = round($amount / 25000, 2);

        $ch = curl_init($this->baseUrl . '/v2/payments/captures/' . $transactionId . '/refund');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'amount' => [
                    'value' => number_format($amountUsd, 2, '.', ''),
                    'currency_code' => 'USD',
                ],
                'note_to_payer' => $reason ?? 'Hoàn tiền từ WebApp Bắc Ninh',
            ]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken,
            ],
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (($result['status'] ?? '') === 'COMPLETED') {
            return PaymentResult::success(
                transactionId: $result['id'] ?? '',
                orderId: $transactionId,
                amount: $amount,
                rawData: $result,
                message: 'Hoàn tiền thành công',
            );
        }

        return PaymentResult::failed('Hoàn tiền thất bại: ' . ($result['message'] ?? 'Unknown error'));
    }

    public function queryTransaction(string $transactionId): PaymentResult
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return PaymentResult::failed('Không thể xác thực với PayPal');
        }

        $ch = curl_init($this->baseUrl . '/v2/checkout/orders/' . $transactionId);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $accessToken,
            ],
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (($result['status'] ?? '') === 'COMPLETED') {
            return PaymentResult::success(
                transactionId: $transactionId,
                orderId: $result['purchase_units'][0]['reference_id'] ?? '',
                amount: 0,
                rawData: $result,
            );
        }

        return PaymentResult::pending($transactionId);
    }

    /**
     * Get PayPal OAuth access token.
     */
    private function getAccessToken(): ?string
    {
        $ch = curl_init($this->baseUrl . '/v1/oauth2/token');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            CURLOPT_USERPWD => $this->clientId . ':' . $this->clientSecret,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        return $result['access_token'] ?? null;
    }
}
