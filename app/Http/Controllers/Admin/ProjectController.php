<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Enums\ProjectCategory;
use App\Services\ProjectService;
use App\Http\Requests\Admin\StoreProjectRequest;
use App\Http\Requests\Admin\UpdateProjectRequest;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index()
    {
        $projects = Project::orderBy('order')->paginate(10);
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $categories = ProjectCategory::options();
        return view('admin.projects.create', compact('categories'));
    }

    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();
        $data['is_featured'] = $request->has('is_featured');
        
        $this->projectService->create($data);

        return redirect()->route('admin.projects.index')->with('success', 'Tạo dự án thành công!');
    }

    public function edit(Project $project)
    {
        $categories = ProjectCategory::options();
        return view('admin.projects.edit', compact('project', 'categories'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $data = $request->validated();
        $data['is_featured'] = $request->has('is_featured');
        
        $this->projectService->update($project, $data);

        return redirect()->route('admin.projects.index')->with('success', 'Cập nhật dự án thành công!');
    }

    public function destroy(Project $project)
    {
        $this->projectService->delete($project);

        return redirect()->route('admin.projects.index')->with('success', 'Xóa dự án thành công!');
    }

    /**
     * Update sorting order via AJAX.
     */
    public function updateOrder(Request $request)
    {
        $request->validate(['order' => 'required|array']);
        
        $this->projectService->updateOrder($request->order);

        return response()->json(['success' => true]);
    }

    /**
     * Bulk delete projects.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = json_decode($request->ids, true);
        
        if (empty($ids)) {
            return redirect()->route('admin.projects.index')->with('error', 'Không có mục nào được chọn.');
        }

        $deleted = $this->projectService->bulkDelete($ids);

        return redirect()->route('admin.projects.index')->with('success', "Đã xóa {$deleted} dự án!");
    }
}
