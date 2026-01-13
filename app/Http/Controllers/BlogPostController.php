<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = Post::where('is_published', true)
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(9);
            
        return view('frontend.blog.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
            
        return view('frontend.blog.show', compact('post'));
    }
}
