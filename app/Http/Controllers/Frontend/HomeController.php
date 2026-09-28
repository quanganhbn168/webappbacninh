<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\PricingGroup;
use App\Models\Post;
use App\Models\PricingPlan;
use App\Models\Product;
use App\Models\Project;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;

class HomeController extends FrontendController
{
    public function __invoke(): View
    {
        return $this->sitePage('home', 'site.home', [
            'products' => Product::query()->forCatalog()->where('is_featured', true)->take(7)->get(),
            'projects' => Project::query()->forCatalog()->take(3)->get(),
            'plans' => PricingPlan::query()->inGroup(PricingGroup::Website)->take(4)->get(),
            'testimonials' => Testimonial::query()->active()->take(3)->get(),
            'posts' => Post::query()->forCatalog()->take(4)->get(),
            'schemaType' => 'WebPage',
        ]);
    }
}
