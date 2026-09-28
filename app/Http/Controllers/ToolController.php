<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Frontend\FrontendController;
use App\Models\MiniApp;
use Illuminate\Contracts\View\View;

class ToolController extends FrontendController
{
    /** The tool list; entries are the mini apps managed in the admin. */
    public function index(): View
    {
        $tools = MiniApp::query()->active()->ordered()->get();

        return $this->sitePage('tools', 'site.tools.index', [
            'tools' => $tools,
            'schemaType' => 'CollectionPage',
            'schemaItems' => $tools->map(fn (MiniApp $tool): array => ['name' => $tool->name, 'url' => url($tool->link)])->all(),
        ]);
    }

    public function qrCode(): View
    {
        return $this->toolPage('qr-code');
    }

    public function bankQr(): View
    {
        return $this->toolPage('bank-qr', ['banks' => config('vietqr_banks.banks')]);
    }

    public function calendar(): View
    {
        return $this->toolPage('calendar');
    }

    public function foodWheel(): View
    {
        return $this->toolPage('food-wheel');
    }
}
