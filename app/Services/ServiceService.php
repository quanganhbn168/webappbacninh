<?php

namespace App\Services;

use App\Models\Service;
use App\Models\Slug;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class ServiceService
{
    public function create(array $data)
    {
        $data['slug'] = Str::slug($data['slug'] ?? $data['title']);
        $this->ensureSlugIsAvailable($data['slug']);

        return Service::create($data);
    }

    public function update(Service $service, array $data)
    {
        $data['slug'] = Str::slug($data['slug'] ?? $data['title']);
        $this->ensureSlugIsAvailable($data['slug'], $service);

        $service->update($data);
        return $service;
    }

    public function delete(Service $service)
    {
        return $service->delete();
    }

    public function bulkDelete(array $ids)
    {
        return Service::destroy($ids);
    }

    public function updateOrder(array $order)
    {
        foreach ($order as $index => $id) {
            Service::where('id', $id)->update(['order' => $index + 1]);
        }
    }

    private function ensureSlugIsAvailable(string $slug, ?Service $service = null): void
    {
        $query = Slug::where('key', $slug);

        if ($service) {
            $query->where(function ($query) use ($service): void {
                $query->where('reference_type', '!=', $service->getMorphClass())
                    ->orWhere('reference_id', '!=', $service->getKey());
            });
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'slug' => 'Đường dẫn này đã được sử dụng bởi nội dung khác.',
            ]);
        }
    }
}
