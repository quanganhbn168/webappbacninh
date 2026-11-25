<?php

namespace App\Filament\Resources\PricingPlans\Schemas;

use App\Enums\PricingGroup;
use App\Filament\Forms\IconPicker;
use App\Models\OperationService;
use App\Models\Service;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\MorphToSelect\Type;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PricingPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                Group::make([
                    Section::make('Gói giá')->schema([
                        TextInput::make('name')->label('Tên gói')->required()->maxLength(255),
                        Group::make([
                            TextInput::make('price_prefix')->label('Chữ trước giá')->maxLength(30)->placeholder('Từ'),
                            TextInput::make('price')->label('Giá')->required()->maxLength(60)->placeholder('12.000.000đ'),
                            TextInput::make('price_suffix')->label('Chữ sau giá')->maxLength(30)->placeholder('/năm'),
                        ])->columns(3),
                        Textarea::make('summary')->label('Mô tả ngắn')->rows(2),
                        Textarea::make('features')->label('Hạng mục trong gói')->rows(6)
                            ->helperText('Mỗi dòng một hạng mục. Dán thẳng từ Excel hoặc Word được.')
                            ->formatStateUsing(fn (mixed $state): string => is_array($state) ? implode("\n", $state) : (string) $state)
                            ->dehydrateStateUsing(fn (?string $state): array => array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $state))))),
                    ])->columns(1),
                ])->columnSpan(['default' => 1, 'lg' => 2]),
                Group::make([
                    Section::make('Gói hiện ở đâu')->schema([
                        MorphToSelect::make('priceable')->label('Thuộc dịch vụ')
                            ->types([
                                Type::make(Service::class)->label('Dịch vụ')->titleAttribute('title'),
                                Type::make(OperationService::class)->label('Dịch vụ vận hành')->titleAttribute('title'),
                            ])
                            ->searchable()->preload(),
                        Text::make('Trang của dịch vụ được chọn hiện gói này; gói đứng đầu là "Giá từ" của dịch vụ.'),
                        Toggle::make('show_in_table')->label('Hiện ở bảng giá chung')->default(true)->live(),
                        Select::make('group')->label('Nhóm trong bảng giá')->options(PricingGroup::options())->native(false)
                            ->required(fn (Get $get): bool => (bool) $get('show_in_table'))
                            ->visible(fn (Get $get): bool => (bool) $get('show_in_table'))
                            ->helperText('Website hiện ở trang chủ, Dịch vụ, Thiết kế website; Hosting ở trang Hosting; Add-on Hosting ở cuối trang Hosting; Chăm sóc ở trang Dịch vụ vận hành.'),
                    ]),
                    Section::make('Hiển thị')->schema([
                        Toggle::make('is_featured')->label('Gói nổi bật'),
                        TextInput::make('badge')->label('Nhãn gói nổi bật')->maxLength(60)->placeholder('Phổ biến nhất'),
                        TextInput::make('cta_label')->label('Chữ trên nút')->maxLength(60)->placeholder('Nhận tư vấn'),
                        IconPicker::make('icon')->helperText('Dùng cho Dịch vụ bổ sung.'),
                        Toggle::make('is_active')->label('Đang hiển thị')->default(true),
                        TextInput::make('order')->label('Thứ tự')->numeric()->default(0),
                    ]),
                ])->columnSpan(['default' => 1, 'lg' => 1]),
            ]);
    }
}
