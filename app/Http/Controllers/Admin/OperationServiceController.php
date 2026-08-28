<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OperationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OperationServiceController extends Controller
{
    public function index(): View
    {
        return view('admin.operation_services.index', [
            'services' => OperationService::query()->orderBy('order')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.operation_services.create', ['service' => new OperationService()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] ??= Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active');

        OperationService::create($data);

        return redirect()->route('admin.operation-services.index')->with('success', 'Đã thêm dịch vụ vận hành.');
    }

    public function edit(OperationService $operationService): View
    {
        return view('admin.operation_services.edit', ['service' => $operationService]);
    }

    public function update(Request $request, OperationService $operationService): RedirectResponse
    {
        $data = $this->validated($request, $operationService);
        $data['is_active'] = $request->boolean('is_active');

        $operationService->update($data);

        return redirect()->route('admin.operation-services.index')->with('success', 'Đã cập nhật dịch vụ vận hành.');
    }

    public function destroy(OperationService $operationService): RedirectResponse
    {
        $operationService->delete();

        return redirect()->route('admin.operation-services.index')->with('success', 'Đã xóa dịch vụ vận hành.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?OperationService $service = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:operation_services,slug'.($service ? ','.$service->id : '')],
            'menu_key' => ['nullable', 'string', 'max:100'],
            'eyebrow' => ['nullable', 'string', 'max:255'],
            'highlight' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', 'string', 'max:2048'],
            'secondary_image' => ['nullable', 'string', 'max:2048'],
            'price_from' => ['nullable', 'string', 'max:255'],
            'cadence' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'order' => ['nullable', 'integer', 'min:0'],
            'data' => ['nullable', 'json'],
        ]);

        $data['data'] = filled($data['data'] ?? null) ? json_decode($data['data'], true, flags: JSON_THROW_ON_ERROR) : [];

        return $data;
    }
}
