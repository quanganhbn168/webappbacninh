<?php

namespace App\Filament\Resources\LandingPages\Pages;

use App\Enums\LandingTemplate;
use App\Filament\Resources\LandingPages\LandingPageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLandingPage extends CreateRecord
{
    protected static string $resource = LandingPageResource::class;

    /** A new page opens already filled with the first template's sample, so every field has a value to edit. */
    protected function fillForm(): void
    {
        $template = LandingTemplate::Purehome;

        $this->callHook('beforeFill');
        $this->form->fill($template->sample() + [
            'template' => $template->value,
            'is_active' => true,
            'noindex' => false,
            'slug' => '',
            'content' => $template->defaults(),
        ]);
        $this->callHook('afterFill');
    }
}
