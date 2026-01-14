<?php

namespace App\View\Components\Frontend;

use App\Models\MiniApp;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MiniAppsSection extends Component
{
    public $apps;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->apps = MiniApp::active()->ordered()->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.frontend.mini-apps-section');
    }
}
