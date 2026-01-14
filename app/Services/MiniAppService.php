<?php

namespace App\Services;

use App\Models\MiniApp;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MiniAppService
{
    /**
     * Get paginated mini apps.
     */
    public function getPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return MiniApp::orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get active mini apps for frontend.
     */
    public function getActiveApps(): Collection
    {
        return MiniApp::active()->ordered()->get();
    }

    /**
     * Create a new mini app.
     */
    public function create(array $data): MiniApp
    {
        $data['is_active'] = isset($data['is_active']) ? (bool) $data['is_active'] : false;
        
        // Auto set order to last if not provided
        if (!isset($data['order'])) {
            $data['order'] = MiniApp::max('order') + 1;
        }

        return MiniApp::create($data);
    }

    /**
     * Update a mini app.
     */
    public function update(MiniApp $miniApp, array $data): bool
    {
        $data['is_active'] = isset($data['is_active']) ? (bool) $data['is_active'] : false;
        return $miniApp->update($data);
    }

    /**
     * Delete a mini app.
     */
    public function delete(MiniApp $miniApp): bool
    {
        return $miniApp->delete();
    }

    /**
     * Bulk delete mini apps.
     */
    public function bulkDelete(array $ids): int
    {
        return MiniApp::whereIn('id', $ids)->delete();
    }

    /**
     * Update order of mini apps.
     */
    public function updateOrder(array $orderData): void
    {
        foreach ($orderData as $item) {
            MiniApp::where('id', $item['id'])->update(['order' => $item['order']]);
        }
    }
}
