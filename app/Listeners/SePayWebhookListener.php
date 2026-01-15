<?php

namespace App\Listeners;

use App\Services\Payment\Providers\SePayProvider;
use App\Services\Payment\Enums\PaymentStatus;
use Illuminate\Support\Facades\Log;
use SePay\SePay\Events\SePayWebhookEvent;

class SePayWebhookListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the SePay webhook event.
     * 
     * This is triggered when SePay detects a bank transfer matching our pattern.
     */
    public function handle(SePayWebhookEvent $event): void
    {
        $data = $event->sePayWebhookData;
        $info = $event->info; // Extracted info from content based on pattern
        
        Log::info('SePay Webhook Received', [
            'info' => $info,
            'code' => $data->code,
            'amount' => $data->transferAmount,
            'type' => $data->transferType,
            'bank' => $data->gateway,
        ]);
        
        // Only process incoming transfers
        if ($data->transferType !== 'in') {
            Log::info('SePay: Ignoring outgoing transfer');
            return;
        }
        
        // Get order ID from info or code
        $orderId = $info ?: $data->code;
        
        // Remove pattern prefix if present
        $pattern = setting('sepay_match_pattern') ?: config('sepay.pattern', 'SE');
        if (str_starts_with($orderId, $pattern)) {
            $orderId = substr($orderId, strlen($pattern));
        }
        
        // Here you would update your order status in database
        // Example:
        // $order = Order::where('order_id', $orderId)->first();
        // if ($order) {
        //     $order->update([
        //         'payment_status' => PaymentStatus::COMPLETED,
        //         'transaction_id' => $data->id,
        //         'paid_amount' => $data->transferAmount,
        //         'paid_at' => now(),
        //     ]);
        // }
        
        Log::info('SePay Payment Completed', [
            'order_id' => $orderId,
            'amount' => $data->transferAmount,
            'transaction_id' => $data->id,
        ]);
        
        // You can also dispatch your own event for further processing
        // event(new PaymentCompleted($orderId, $data->transferAmount, 'sepay'));
    }
}
