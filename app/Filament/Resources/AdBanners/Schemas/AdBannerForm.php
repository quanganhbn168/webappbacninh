<?php

namespace App\Filament\Resources\AdBanners\Schemas;

use App\Enums\BannerSlot;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AdBannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')->label('Tên banner')->required()->maxLength(255),
                Select::make('slot')->label('Vị trí')->options(BannerSlot::options())->required()->native(false),
                CuratorPicker::make('image_id')->label('Ảnh banner')->required()->constrained()->columnSpanFull(),
                TextInput::make('link')->label('Link khi bấm')->maxLength(2048),
                TextInput::make('alt_text')->label('Mô tả ảnh (alt)')->maxLength(255),
                DateTimePicker::make('starts_at')->label('Bắt đầu hiển thị')->native(false),
                DateTimePicker::make('ends_at')->label('Kết thúc')->native(false)->after('starts_at'),
                Toggle::make('open_new_tab')->label('Mở tab mới')->default(true),
                Toggle::make('is_active')->label('Đang hiển thị')->default(true),
                TextInput::make('order')->label('Thứ tự')->numeric()->default(0),
            ]);
    }
}
