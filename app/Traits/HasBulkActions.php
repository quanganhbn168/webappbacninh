<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait HasBulkActions
{
    /**
     * Get the service instance.
     * @return mixed
     */
    abstract protected function getService();

    /**
     * Get the route name for redirection after action.
     * @return string
     */
    abstract protected function getIndexRouteName();

    public function bulkDestroy(Request $request)
    {
        $ids = json_decode($request->ids, true);
        
        if (empty($ids)) {
            return redirect()->route($this->getIndexRouteName())->with('error', 'Không có mục nào được chọn.');
        }

        $count = $this->getService()->bulkDelete($ids);

        return redirect()->route($this->getIndexRouteName())->with('success', "Đã xóa {$count} mục thành công!");
    }

    public function updateOrder(Request $request)
    {
        $request->validate(['order' => 'required|array']);
        
        $this->getService()->updateOrder($request->order);

        return response()->json(['success' => true]);
    }
}
