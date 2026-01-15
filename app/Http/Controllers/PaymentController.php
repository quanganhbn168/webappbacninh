<?php

namespace App\Http\Controllers;

use App\Services\Payment\PaymentService;
use App\Services\Payment\DTO\PaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService
    ) {}

    /**
     * Display checkout page with available payment methods.
     */
    public function checkout(Request $request)
    {
        // For demo, we'll create a sample order
        $order = [
            'id' => $request->order_id ?? 'DEMO_' . time(),
            'amount' => (float) ($request->amount ?? 100000),
            'description' => $request->description ?? 'Thanh toán đơn hàng demo',
        ];

        $providers = $this->paymentService->getAvailableProviders();
        $defaultProvider = $this->paymentService->getDefaultProvider();

        return view('payment.checkout', compact('order', 'providers', 'defaultProvider'));
    }

    /**
     * Process payment - redirect to payment gateway.
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|string',
            'amount' => 'required|numeric|min:1000',
            'description' => 'nullable|string|max:255',
            'provider' => 'required|string',
            'customer_email' => 'nullable|email',
            'customer_phone' => 'nullable|string',
        ]);

        // Check if provider exists
        if (!$this->paymentService->hasProvider($validated['provider'])) {
            return back()->with('error', 'Phương thức thanh toán không hợp lệ.');
        }

        // Create payment request
        $paymentRequest = PaymentRequest::fromArray([
            'order_id' => $validated['order_id'],
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? 'Thanh toán đơn hàng ' . $validated['order_id'],
            'return_url' => route('payment.callback', ['provider' => $validated['provider']]),
            'cancel_url' => route('payment.cancel'),
            'customer_email' => $validated['customer_email'] ?? null,
            'customer_phone' => $validated['customer_phone'] ?? null,
            'ip_address' => $request->ip(),
        ]);

        try {
            // Get payment URL from provider
            $paymentUrl = $this->paymentService->createPaymentUrl($paymentRequest, $validated['provider']);

            if (empty($paymentUrl)) {
                return back()->with('error', 'Không thể tạo liên kết thanh toán. Vui lòng thử lại.');
            }

            // Store order info in session for callback
            session([
                'pending_payment' => [
                    'order_id' => $validated['order_id'],
                    'amount' => $validated['amount'],
                    'provider' => $validated['provider'],
                ],
            ]);

            // Redirect to payment gateway
            return redirect($paymentUrl);

        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi xử lý thanh toán: ' . $e->getMessage());
        }
    }

    /**
     * Handle callback from payment gateway.
     */
    public function callback(Request $request, string $provider)
    {
        if (!$this->paymentService->hasProvider($provider)) {
            return redirect()->route('payment.result')->with('error', 'Provider không hợp lệ.');
        }

        try {
            // Verify callback with provider
            $result = $this->paymentService->verifyCallback($request->all(), $provider);

            // Clear pending payment session
            session()->forget('pending_payment');

            // Store result in session
            session(['payment_result' => $result->toArray()]);

            // In a real app, you would update order status in database here
            // Order::find($result->orderId)->update(['status' => $result->status->value]);

            return redirect()->route('payment.result');

        } catch (\Exception $e) {
            session(['payment_result' => [
                'success' => false,
                'message' => 'Lỗi xử lý kết quả: ' . $e->getMessage(),
            ]]);

            return redirect()->route('payment.result');
        }
    }

    /**
     * Handle payment cancellation.
     */
    public function cancel()
    {
        session()->forget('pending_payment');

        return view('payment.cancel');
    }

    /**
     * Display payment result.
     */
    public function result()
    {
        $result = session('payment_result', [
            'success' => false,
            'message' => 'Không có thông tin thanh toán.',
        ]);

        return view('payment.result', compact('result'));
    }

    /**
     * API endpoint for AJAX payment processing.
     */
    public function processApi(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|string',
            'amount' => 'required|numeric|min:1000',
            'description' => 'nullable|string|max:255',
            'provider' => 'required|string',
        ]);

        if (!$this->paymentService->hasProvider($validated['provider'])) {
            return response()->json([
                'success' => false,
                'message' => 'Phương thức thanh toán không hợp lệ.',
            ], 400);
        }

        $paymentRequest = PaymentRequest::fromArray([
            'order_id' => $validated['order_id'],
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? 'Thanh toán đơn hàng',
            'return_url' => route('payment.callback', ['provider' => $validated['provider']]),
            'ip_address' => $request->ip(),
        ]);

        try {
            $paymentUrl = $this->paymentService->createPaymentUrl($paymentRequest, $validated['provider']);

            return response()->json([
                'success' => true,
                'payment_url' => $paymentUrl,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display SePay QR Code page for bank transfer.
     */
    public function sepayQr(Request $request)
    {
        $order_id = $request->get('order_id', 'DEMO_' . time());
        $amount = $request->get('amount', 0);
        $code = $request->get('code', 'SE' . $order_id);
        $bank = $request->get('bank', setting('sepay_bank_name') ?: 'MBBank');
        $account = $request->get('account', setting('sepay_bank_account') ?: '');
        $name = $request->get('name', setting('sepay_account_name') ?: 'WebApp Bac Ninh');
        $return_url = $request->get('return_url', route('payment.result'));

        return view('payment.sepay-qr', compact(
            'order_id', 'amount', 'code', 'bank', 'account', 'name', 'return_url'
        ));
    }
}
