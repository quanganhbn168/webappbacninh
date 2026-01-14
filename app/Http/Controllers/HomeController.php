<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Project;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::where('is_published', true)
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->limit(3)
            ->get();

        $projects = Project::featured()->limit(3)->get();

        return view('frontend.index', compact('posts', 'projects'));
    }
}

