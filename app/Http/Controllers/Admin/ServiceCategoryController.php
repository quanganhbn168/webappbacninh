<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceCategoryRequest;
use App\Http\Requests\Admin\UpdateServiceCategoryRequest;
use App\Models\ServiceCategory;
use App\Models\Slug;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ServiceCategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::withCount('services')->orderBy('order')->paginate(15);

        return view('admin.service_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.service_categories.create');
    }

    public function store(StoreServiceCategoryRequest $request)
    {
        $data = $this->normalise($request->validated(), $request->boolean('is_active'));
        $this->ensureSlugIsAvailable($data['slug']);
        ServiceCategory::create($data);

        return redirect()->route('admin.service-categories.index')->with('success', 'Thêm nhóm dịch vụ thành công.');
    }

    public function edit(ServiceCategory $serviceCategory)
    {
        return view('admin.service_categories.edit', compact('serviceCategory'));
    }

    public function show(ServiceCategory $serviceCategory)
    {
        return redirect()->route('admin.service-categories.edit', $serviceCategory);
    }

    public function update(UpdateServiceCategoryRequest $request, ServiceCategory $serviceCategory)
    {
        $data = $this->normalise($request->validated(), $request->boolean('is_active'));
        $this->ensureSlugIsAvailable($data['slug'], $serviceCategory);
        $serviceCategory->update($data);

        return redirect()->route('admin.service-categories.index')->with('success', 'Cập nhật nhóm dịch vụ thành công.');
    }

    public function destroy(ServiceCategory $serviceCategory)
    {
        try {
            $serviceCategory->delete();

            return redirect()->route('admin.service-categories.index')->with('success', 'Đã xóa nhóm dịch vụ.');
        } catch (\DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    private function normalise(array $data, bool $isActive): array
    {
        $data['slug'] = Str::slug($data['slug'] ?? $data['name']);
        $data['is_active'] = $isActive;
        $data['order'] = $data['order'] ?? 0;

        return $data;
    }

    private function ensureSlugIsAvailable(string $slug, ?ServiceCategory $category = null): void
    {
        $query = Slug::where('key', $slug);

        if ($category) {
            $query->where(function ($query) use ($category): void {
                $query->where('reference_type', '!=', $category->getMorphClass())
                    ->orWhere('reference_id', '!=', $category->getKey());
            });
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'slug' => 'Đường dẫn này đã được sử dụng bởi nội dung khác.',
            ]);
        }
    }
}
