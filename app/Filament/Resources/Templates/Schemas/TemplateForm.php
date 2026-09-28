<?php

namespace App\Filament\Resources\Templates\Schemas;

use App\Enums\TemplateType;
use App\Filament\Forms\PermalinkInput;
use App\Models\Template;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                Group::make([
                    Section::make('Thông tin giao diện')->schema([
                        TextInput::make('name')->label('Tên giao diện')->required()->maxLength(255)->live(onBlur: true)
                            ->placeholder('Food House - Nhà hàng và ẩm thực')
                            ->afterStateUpdated(function (Set $set, Get $get, ?Template $record, ?string $state): void {
                                if (! $record && ! $get('slug_manually_edited')) {
                                    $set('slug', Str::slug($state ?? ''));
                                }
                            }),
                        Hidden::make('slug_manually_edited')->default(false)->dehydrated(false),
                        PermalinkInput::make('slug')->prefix(url('/kho-giao-dien').'/')->required()
                            ->unique(ignoreRecord: true)
                            ->afterStateUpdated(fn (Set $set) => $set('slug_manually_edited', true)),
                        Group::make([
                            TextInput::make('code')->label('Mã giao diện')->required()->maxLength(50)
                                ->placeholder('WABN-013')->unique(ignoreRecord: true)
                                ->helperText('Khách dùng mã này khi gửi yêu cầu tư vấn.'),
                            TextInput::make('badge')->label('Nhãn')->maxLength(50)->placeholder('Bán chạy, Mới, Nổi bật…'),
                        ])->columns(2),
                        Group::make([
                            Select::make('type')->label('Loại website')->options(TemplateType::options())
                                ->required()->native(false),
                            Select::make('template_category_id')->label('Ngành')->relationship('category', 'name')
                                ->required()->searchable()->preload()
                                ->createOptionForm([
                                    TextInput::make('name')->label('Tên ngành')->required()->maxLength(255),
                                    TextInput::make('slug')->label('Đường dẫn')->required()->maxLength(255)
                                        ->helperText('Chữ thường không dấu, dùng cho bộ lọc. Ví dụ: nha-hang'),
                                ]),
                        ])->columns(2),
                        Textarea::make('description')->label('Mô tả ngắn')->rows(3)->required()
                            ->helperText('Hiển thị trên thẻ giao diện và đầu trang chi tiết.'),
                        TagsInput::make('tags')->label('Thẻ nổi bật')->placeholder('Thêm thẻ rồi nhấn Enter')
                            ->helperText('Tối đa 3 thẻ đầu tiên hiện trên thẻ giao diện.'),
                    ])->columns(1),
                    Section::make('Nội dung trang chi tiết')->schema([
                        self::list('audiences', 'Mẫu này phù hợp với ai?', 'Doanh nghiệp cần website rõ dịch vụ, dễ nhận khách'),
                        self::list('pages', 'Các trang có sẵn', 'Trang chủ'),
                        self::list('included_features', 'Chức năng và tiêu chuẩn bàn giao', 'Form liên hệ và nút gọi / Zalo nổi'),
                        self::list('customizations', 'Có thể tùy chỉnh', 'Đổi màu thương hiệu và logo'),
                    ])->columns(1),
                    Section::make('SEO')->collapsed()->schema([
                        TextInput::make('meta_title')->label('Tiêu đề SEO')->maxLength(255)
                            ->helperText('Bỏ trống sẽ dùng "Tên giao diện | Tên website".'),
                        Textarea::make('meta_description')->label('Mô tả SEO')->rows(3)
                            ->helperText('Bỏ trống sẽ dùng mô tả ngắn.'),
                    ])->columns(1),
                ])->columnSpan(['default' => 1, 'lg' => 2]),
                Group::make([
                    Section::make('Chi phí và thời gian')->schema([
                        TextInput::make('price')->label('Chi phí tham khảo từ')->numeric()->minValue(0)->suffix('đ')->required(),
                        TextInput::make('sale_price')->label('Giá khuyến mãi')->numeric()->minValue(0)->suffix('đ'),
                        TextInput::make('duration')->label('Thời gian triển khai')->maxLength(100)->placeholder('7 - 10 ngày'),
                        TextInput::make('year')->label('Năm')->numeric()->minValue(2000)->maxValue(2100)->default(now()->year),
                        TextInput::make('demo_url')->label('Link demo')->url()->maxLength(255),
                    ])->columns(1),
                    Section::make('Tính năng lọc')->schema([
                        CheckboxList::make('features')->hiddenLabel()->relationship('features', 'name')
                            ->helperText('Dùng cho bộ lọc "Tính năng" ở trang Kho giao diện.'),
                    ]),
                    Section::make('Hình ảnh')->schema([
                        CuratorPicker::make('image_id')->label('Ảnh đại diện')->constrained(),
                        CuratorPicker::make('gallery')->label('Bộ ảnh chi tiết')->multiple()->constrained()
                            ->helperText('Ảnh đầu tiên là ảnh lớn ở trang chi tiết.'),
                    ])->columns(1),
                    Section::make('Hiển thị')->schema([
                        Toggle::make('is_featured')->label('Nổi bật'),
                        Toggle::make('is_active')->label('Đang hiển thị')->default(true),
                        TextInput::make('order')->label('Thứ tự')->numeric()->default(0),
                    ])->columns(1),
                ])->columnSpan(['default' => 1, 'lg' => 1]),
            ]);
    }

    private static function list(string $name, string $label, string $placeholder): Repeater
    {
        return Repeater::make($name)->label($label)
            ->simple(TextInput::make('value')->required()->maxLength(255)->placeholder($placeholder))
            ->addActionLabel('Thêm dòng')->reorderable()->defaultItems(0);
    }
}
