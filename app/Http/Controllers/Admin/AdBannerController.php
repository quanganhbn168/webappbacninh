<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdBanner;
use App\Enums\BannerSlot;
use App\Services\AdBannerService;
use App\Http\Requests\Admin\StoreAdBannerRequest;
use App\Http\Requests\Admin\UpdateAdBannerRequest;
use Illuminate\Http\Request;

class AdBannerController extends Controller
{
    use \App\Traits\HasBulkActions;

    protected AdBannerService $adBannerService;

    public function __construct(AdBannerService $adBannerService)
    {
        $this->adBannerService = $adBannerService;
    }

    protected function getService()
    {
        return $this->adBannerService;
    }

    protected function getIndexRouteName()
    {
        return 'admin.ad-banners.index';
    }

    public function index()
    {
        $banners = AdBanner::orderBy('order')->paginate(15);
        return view('admin.ad-banners.index', compact('banners'));
    }

    public function create()
    {
        $slots = BannerSlot::options();
        return view('admin.ad-banners.create', compact('slots'));
    }

    public function store(StoreAdBannerRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');
        $data['open_new_tab'] = $request->has('open_new_tab');

        $this->adBannerService->create($data);

        return redirect()->route('admin.ad-banners.index')->with('success', 'Tạo banner thành công!');
    }

    public function edit(AdBanner $adBanner)
    {
        $slots = BannerSlot::options();
        return view('admin.ad-banners.edit', compact('adBanner', 'slots'));
    }

    public function update(UpdateAdBannerRequest $request, AdBanner $adBanner)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');
        $data['open_new_tab'] = $request->has('open_new_tab');

        $this->adBannerService->update($adBanner, $data);

        return redirect()->route('admin.ad-banners.index')->with('success', 'Cập nhật banner thành công!');
    }

    public function destroy(AdBanner $adBanner)
    {
        $this->adBannerService->delete($adBanner);

        return redirect()->route('admin.ad-banners.index')->with('success', 'Xóa banner thành công!');
    }
}
