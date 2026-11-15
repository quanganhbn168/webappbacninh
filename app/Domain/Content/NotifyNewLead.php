<?php

namespace App\Domain\Content;

use App\Models\Lead;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/** Sends a new contact request to the owner's Telegram chat. Never throws: a failed alert must not lose the lead. */
class NotifyNewLead
{
    public function __invoke(Lead $lead): void
    {
        $token = config('services.telegram.bot_token');
        $chat = config('services.telegram.chat_id');

        if (! $token || ! $chat) {
            return;
        }

        try {
            Http::timeout(8)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chat,
                'text' => $this->message($lead),
                'disable_web_page_preview' => true,
            ])->throw();
        } catch (Throwable $e) {
            Log::warning('Telegram lead alert failed', ['lead' => $lead->id, 'error' => $e->getMessage()]);
        }
    }

    private function message(Lead $lead): string
    {
        $lines = array_filter([
            '🔔 Khách mới cần tư vấn',
            'Tên: '.$lead->name,
            'SĐT: '.$lead->phone,
            $lead->email ? 'Email: '.$lead->email : null,
            $lead->company ? 'Doanh nghiệp: '.$lead->company : null,
            $lead->need ? 'Nhu cầu: '.$lead->need : null,
            $lead->message ? 'Ghi chú: '.mb_substr($lead->message, 0, 500) : null,
            $lead->source ? 'Từ trang: '.$lead->source : null,
        ]);

        return implode("\n", $lines);
    }
}
