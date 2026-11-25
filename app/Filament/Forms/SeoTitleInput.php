<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;

/**
 * SEO title of a public page. The site name is added when the page is rendered
 * (App\Domain\Content\AppendSiteName), so the admin neither shows nor asks for it.
 */
class SeoTitleInput extends TextInput
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Tiêu đề SEO')
            ->maxLength(255)
            ->placeholder('Bỏ trống: tự lấy tên');
    }
}
