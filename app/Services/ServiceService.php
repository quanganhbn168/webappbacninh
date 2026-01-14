<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Support\Str;

class ServiceService
{
    public function create(array $data)
    {
        $data['slug'] = Str::slug($data['title']);
        return Service::create($data);
    }

    public function update(Service $service, array $data)
    {
        $data['slug'] = Str::slug($data['title']);
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
}
