<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadRequest;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class LeadController extends Controller
{
    public function store(StoreLeadRequest $request): JsonResponse|RedirectResponse
    {
        $data = $request->validated();
        $data['company'] ??= $data['business'] ?? null;
        unset($data['business']);
        $data['source'] ??= $request->headers->get('referer');
        $data['ip_address'] = $request->ip();
        $data['user_agent'] = mb_substr((string) $request->userAgent(), 0, 1000);
        $data['status'] = 'new';
        Lead::create($data);

        $message = 'Cảm ơn anh/chị. WebApp Bắc Ninh sẽ liên hệ lại sớm nhất.';

        return $request->expectsJson()
            ? response()->json(['message' => $message], 201)
            : back()->with('success', $message);
    }
}
