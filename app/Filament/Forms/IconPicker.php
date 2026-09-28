<?php

namespace App\Filament\Forms;

use App\Support\Icons;
use Filament\Forms\Components\Select;

/**
 * Picks an icon from the site's icon set (see App\Support\Icons) with a preview.
 */
class IconPicker extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Biểu tượng')
            ->options(fn (): array => Icons::options())
            ->allowHtml()
            ->searchable()
            ->native(false)
            ->placeholder('Chọn biểu tượng');
    }
}
