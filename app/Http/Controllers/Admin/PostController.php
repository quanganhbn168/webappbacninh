<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Tag;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        $posts = Post::with('category', 'tags')->latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = PostCategory::active()->pluck('name', 'id');
        $allTags = Tag::pluck('name', 'id');
        return view('admin.posts.create', compact('categories', 'allTags'));
    }

    public function store(StorePostRequest $request)
    {
        $this->postService->create($request->validated());
        
        return redirect()
            ->route('admin.blog.index')
            ->with('success', 'Tạo bài viết mới thành công!');
    }

    public function edit(Post $post)
    {
        $categories = PostCategory::active()->pluck('name', 'id');
        $allTags = Tag::pluck('name', 'id');
        $selectedTags = $post->tags->pluck('id')->toArray();
        return view('admin.posts.edit', compact('post', 'categories', 'allTags', 'selectedTags'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->postService->update($post, $request->validated());
        
        return redirect()
            ->route('admin.blog.index')
            ->with('success', 'Cập nhật bài viết thành công!');
    }

    public function destroy(Post $post)
    {
        $this->postService->delete($post);
        
        return redirect()
            ->route('admin.blog.index')
            ->with('success', 'Xóa bài viết thành công!');
    }

    /**
     * Upload image for TinyMCE editor.
     */
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('posts/content', 'public');
            return response()->json(['location' => asset('storage/' . $path)]);
        }
        return response()->json(['error' => 'Tải ảnh thất bại'], 400);
    }

    /**
     * Check slug uniqueness via AJAX.
     */
    public function checkSlug(Request $request)
    {
        $slug = Str::slug($request->slug);
        $exists = Post::where('slug', $slug)
            ->when($request->exclude_id, fn($q, $id) => $q->where('id', '!=', $id))
            ->exists();
        
        return response()->json([
            'exists' => $exists,
            'slug' => $slug,
            'message' => $exists ? 'Slug này đã tồn tại, vui lòng chọn slug khác.' : 'Slug hợp lệ!'
        ]);
    }
}
