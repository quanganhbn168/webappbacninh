<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use Illuminate\Contracts\View\View;

class LandingPageController extends Controller
{
    public function show(string $slug): View
    {
        $landing = LandingPage::query()->active()->where('slug', $slug)->firstOrFail();

        return view($landing->template->view(), [
            'landing' => $landing,
            'c' => $landing->data(),
        ]);
    }
}
