<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\TemplateCategory;
use Illuminate\Http\Request;

class TemplateController extends Controller
{

    public function index(Request $request)
    {
        $query = Template::active()->ordered()->with('templateCategory');

        if ($request->has('category') && $request->category != 'all') {
            $query->whereHas('templateCategory', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        $templates = $query->paginate(12);

        // Get categories with at least one active template
        $categories = TemplateCategory::whereHas('templates', function($q){
            $q->active();
        })->get();

        return view('frontend.templates.index', compact('templates', 'categories'));
    }

    public function show(Template $template)
    {
        if (!$template->is_active) {
            abort(404);
        }
        return view('frontend.templates.show', compact('template'));
    }
}
