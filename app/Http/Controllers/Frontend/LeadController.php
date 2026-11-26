<?php

namespace App\Http\Controllers\Frontend;

use App\Domain\Content\NotifyNewLead;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadRequest;
use App\Models\LandingPage;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class LeadController extends Controller
{
    public function store(StoreLeadRequest $request): JsonResponse|RedirectResponse
    {
        $message = 'Cảm ơn anh/chị. WebApp Bắc Ninh sẽ liên hệ lại sớm nhất.';
        $reply = fn () => $request->expectsJson()
            ? response()->json(['message' => $message], 201)
            : back()->with('success', $message);

        // Honeypot: real visitors never see this field, bots fill it. Pretend it worked.
        if ($request->filled('website')) {
            return $reply();
        }

        $data = $request->validated();
        $data['company'] ??= $data['business'] ?? null;
        unset($data['business']);
        $data['source'] ??= $request->headers->get('referer');
        if (preg_match('#^/landing/([A-Za-z0-9_-]+)#', (string) $data['source'], $match)) {
            $data['landing_page_id'] = LandingPage::query()->where('slug', $match[1])->value('id');
        }
        if (! empty($data['campaign'])) {
            $data['meta'] = ['campaign' => $data['campaign']];
        }
        unset($data['campaign']);
        $data['ip_address'] = $request->ip();
        $data['user_agent'] = mb_substr((string) $request->userAgent(), 0, 1000);
        $data['status'] = 'new';
        $lead = Lead::create($data);

        defer(fn () => app(NotifyNewLead::class)($lead));

        return $reply();
    }
}
