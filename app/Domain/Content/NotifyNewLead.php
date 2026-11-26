<?php

namespace App\Domain\Content;

use App\Filament\Resources\Leads\LeadResource;
use App\Models\Lead;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Throwable;

/**
 * Announces a new contact request: in the admin bell of everyone who can see leads,
 * and in the owner's Telegram chat. Never throws: a failed alert must not lose the lead.
 */
class NotifyNewLead
{
    public function __invoke(Lead $lead): void
    {
        $this->toAdminBell($lead);
        $this->toTelegram($lead);
    }

    private function toAdminBell(Lead $lead): void
    {
        try {
            // Leads have no policy class: access comes from the Shield permission or the super admin role.
            $admins = User::role(config('filament-shield.super_admin.name', 'super_admin'), 'admin')->get()
                ->merge(User::permission(Permission::findByName('ViewAny:Lead', 'admin'))->get())
                ->unique('id');
            if ($admins->isEmpty()) {
                return;
            }

            Notification::make()
                ->title('Khách mới: '.$lead->name)
                ->body(implode(' · ', array_filter([$lead->phone, $lead->need])))
                ->icon('heroicon-o-user-plus')
                ->iconColor('success')
                ->actions([Action::make('open')->label('Xem liên hệ')->url(LeadResource::getUrl('index', panel: 'admin'))->markAsRead()])
                ->sendToDatabase($admins);
        } catch (Throwable $e) {
            Log::warning('Admin bell lead alert failed', ['lead' => $lead->id, 'error' => $e->getMessage()]);
        }
    }

    private function toTelegram(Lead $lead): void
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
            $lead->campaignLabel() ? 'Quảng cáo: '.$lead->campaignLabel() : null,
        ]);

        return implode("\n", $lines);
    }
}
