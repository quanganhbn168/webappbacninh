<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;

class PermalinkInput extends TextInput
{
    protected string $view = 'filament.forms.permalink-input';

    protected function setUp(): void
    {
        parent::setUp();
        $this->label('Đường dẫn')->maxLength(255)->live(onBlur: true)
            ->helperText('Tự tạo từ tiêu đề. Đường dẫn trùng sẽ được thêm số khi lưu.');
    }
}
