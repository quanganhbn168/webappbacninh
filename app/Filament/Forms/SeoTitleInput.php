<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;

/**
 * SEO title of a public page. The site name is added automatically
 * (App\Domain\Content\AppendSiteName), so the field shows it as a fixed suffix.
 */
class SeoTitleInput extends TextInput
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Tiêu đề SEO')
            ->maxLength(255)
            ->suffix(fn (): string => '| '.site_config('name'))
            ->placeholder('Bỏ trống: tự lấy tên')
            ->helperText('Không cần gõ tên website, hệ thống tự thêm phía sau.');
    }
}
