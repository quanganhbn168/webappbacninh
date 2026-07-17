<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Http\Requests\Admin\StoreTemplateRequest;
use App\Http\Requests\Admin\UpdateTemplateRequest;
use App\Services\TemplateService;
use App\Models\TemplateCategory;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Models\Slug;
use Illuminate\Support\Str;
use App\Traits\HasBulkActions;

class TemplateController extends Controller
{
    use HasBulkActions;

    protected TemplateService $templateService;

    public function __construct(TemplateService $templateService)
    {
        $this->templateService = $templateService;
    }

    protected function getService()
    {
        return $this->templateService;
    }

    protected function getIndexRouteName()
    {
        return 'admin.templates.index';
    }

    public function index()
    {
        $templates = Template::ordered()->paginate(10);
        return view('admin.templates.index', compact('templates'));
    }


    public function create()
    {
        $categories = TemplateCategory::all();
        $tags = Tag::pluck('name', 'id');
        return view('admin.templates.create', compact('categories', 'tags'));
    }

    public function store(StoreTemplateRequest $request)
    {
        $data = $request->validated();
        $this->templateService->create($data);

        return redirect()->route('admin.templates.index')->with('success', 'Thêm giao diện thành công!');
    }

    public function edit(Template $template)
    {
        $categories = TemplateCategory::all();
        $tags = Tag::pluck('name', 'id');
        return view('admin.templates.edit', compact('template', 'categories', 'tags'));
    }

    public function update(UpdateTemplateRequest $request, Template $template)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');
        $data['is_premium'] = $request->has('is_premium');
        $data['is_free'] = $request->has('is_free');

        $this->templateService->update($template, $data);

        return redirect()->route('admin.templates.index')->with('success', 'Cập nhật giao diện thành công!');
    }

    public function destroy(Template $template)
    {
        $this->templateService->delete($template);
        return redirect()->route('admin.templates.index')->with('success', 'Xóa giao diện thành công!');
    }

}
