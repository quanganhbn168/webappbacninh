<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TemplateCategory;
use App\Http\Requests\Admin\StoreTemplateCategoryRequest;
use App\Http\Requests\Admin\UpdateTemplateCategoryRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TemplateCategoryController extends Controller
{
    public function index()
    {
        $categories = TemplateCategory::latest()->paginate(10);
        return view('admin.template_categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = TemplateCategory::whereNull('parent_id')->pluck('name', 'id');
        return view('admin.template_categories.create', compact('parents'));
    }

    public function store(StoreTemplateCategoryRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $category = TemplateCategory::create($data);
        $this->importMedia($category, $data);

        return redirect()->route('admin.template-categories.index')
            ->with('success', 'Thêm danh mục thành công!');
    }

    public function edit(TemplateCategory $templateCategory)
    {
        $parents = TemplateCategory::whereNull('parent_id')
            ->where('id', '!=', $templateCategory->id)
            ->pluck('name', 'id');
        return view('admin.template_categories.edit', compact('templateCategory', 'parents'));
    }

    public function update(UpdateTemplateCategoryRequest $request, TemplateCategory $templateCategory)
    {
        $data = $request->validated();
        // Option to update slug or keep it fixed, usually update if name changes
        $data['slug'] = Str::slug($data['name']);

        $templateCategory->update($data);
        $this->importMedia($templateCategory, $data);

        return redirect()->route('admin.template-categories.index')
            ->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy(TemplateCategory $templateCategory)
    {
        try {
            $templateCategory->delete();

            return redirect()->route('admin.template-categories.index')
                ->with('success', 'Xóa danh mục thành công!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function bulkDestroy(Request $request)
    {
        $ids = json_decode($request->input('ids'));
        
        if (!is_array($ids)) {
            $ids = explode(',', $request->input('ids'));
        }
        
        $count = 0;
        $errors = [];

        foreach ($ids as $id) {
            $category = TemplateCategory::find($id);
            if ($category) {
                try {
                    $category->delete();
                    $count++;
                } catch (\Exception $e) {
                    $errors[] = "ID $id: " . $e->getMessage();
                }
            }
        }

        if (count($errors) > 0) {
            return redirect()->route('admin.template-categories.index')
                ->with('warning', "Deleted $count categories. Errors: " . implode('; ', $errors));
        }

        return redirect()->route('admin.template-categories.index')
                ->with('success', "Xóa thành công $count danh mục!");
    }

    private function importMedia(TemplateCategory $category, array $data): void
    {
        if (!empty($data['image'])) {
            $category->importMediaFromLegacyPath($data['image'], 'featured');
        }

        if (!empty($data['og_image'])) {
            $category->importMediaFromLegacyPath($data['og_image'], 'og');
        }
    }
}
