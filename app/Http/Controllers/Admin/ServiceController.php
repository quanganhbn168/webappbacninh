<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Services\ServiceService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use \App\Traits\HasBulkActions;

    protected ServiceService $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    protected function getService()
    {
        return $this->serviceService;
    }

    protected function getIndexRouteName()
    {
        return 'admin.services.index';
    }

    public function index()
    {
        $services = Service::with('category')->ordered()->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $categories = ServiceCategory::active()->pluck('name', 'id');

        return view('admin.services.create', compact('categories'));
    }

    public function store(StoreServiceRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');
        
        $this->serviceService->create($data);

        return redirect()->route('admin.services.index')->with('success', 'Thêm dịch vụ thành công!');
    }

    public function edit(Service $service)
    {
        $categories = ServiceCategory::active()->pluck('name', 'id');

        return view('admin.services.edit', compact('service', 'categories'));
    }

    public function update(UpdateServiceRequest $request, Service $service)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');

        $this->serviceService->update($service, $data);

        return redirect()->route('admin.services.index')->with('success', 'Cập nhật dịch vụ thành công!');
    }

    public function destroy(Service $service)
    {
        $this->serviceService->delete($service);
        return redirect()->route('admin.services.index')->with('success', 'Xóa dịch vụ thành công!');
    }
}
