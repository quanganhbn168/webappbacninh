<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMiniAppRequest;
use App\Http\Requests\Admin\UpdateMiniAppRequest;
use App\Models\MiniApp;
use App\Services\MiniAppService;
use App\Traits\HasBulkActions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MiniAppController extends Controller
{
    use HasBulkActions;

    protected $miniAppService;

    public function __construct(MiniAppService $miniAppService)
    {
        $this->miniAppService = $miniAppService;
    }

    /**
     * Get the service instance for the trait.
     */
    protected function getService()
    {
        return $this->miniAppService;
    }

    /**
     * Get the index route name for the trait.
     */
    protected function getIndexRouteName()
    {
        return 'admin.mini-apps.index';
    }

    public function index()
    {
        $miniApps = $this->miniAppService->getPaginated();
        return view('admin.mini_apps.index', compact('miniApps'));
    }

    public function create()
    {
        return view('admin.mini_apps.create');
    }

    public function store(StoreMiniAppRequest $request)
    {
        $this->miniAppService->create($request->validated());
        return redirect()->route('admin.mini-apps.index')->with('success', 'Thêm ứng dụng mới thành công!');
    }

    public function edit(MiniApp $miniApp)
    {
        return view('admin.mini_apps.edit', compact('miniApp'));
    }

    public function update(UpdateMiniAppRequest $request, MiniApp $miniApp)
    {
        $this->miniAppService->update($miniApp, $request->validated());
        return redirect()->route('admin.mini-apps.index')->with('success', 'Cập nhật ứng dụng thành công!');
    }

    public function destroy(MiniApp $miniApp)
    {
        $this->miniAppService->delete($miniApp);
        return redirect()->route('admin.mini-apps.index')->with('success', 'Xóa ứng dụng thành công!');
    }
}
