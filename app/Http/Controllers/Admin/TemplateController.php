<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Http\Requests\Admin\StoreTemplateRequest;
use App\Http\Requests\Admin\UpdateTemplateRequest;
use App\Services\TemplateService;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    use \App\Traits\HasBulkActions;

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
        return view('admin.templates.create');
    }

    public function store(StoreTemplateRequest $request)
    {
        $data = $request->validated();
        $this->templateService->create($data, $request->file('image_file'));

        return redirect()->route('admin.templates.index')->with('success', 'Thêm giao diện thành công!');
    }

    public function edit(Template $template)
    {
        return view('admin.templates.edit', compact('template'));
    }

    public function update(UpdateTemplateRequest $request, Template $template)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');
        $data['is_premium'] = $request->has('is_premium');

        $this->templateService->update($template, $data, $request->file('image_file'));

        return redirect()->route('admin.templates.index')->with('success', 'Cập nhật giao diện thành công!');
    }

    public function destroy(Template $template)
    {
        $this->templateService->delete($template);
        return redirect()->route('admin.templates.index')->with('success', 'Xóa giao diện thành công!');
    }
}
