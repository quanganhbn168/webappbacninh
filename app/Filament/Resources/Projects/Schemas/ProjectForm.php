<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Filament\Forms\PermalinkInput;
use App\Models\Project;
use Awcodes\Curator\Components\Forms\CuratorPicker;
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

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                Group::make([
                    Section::make('Thông tin dự án')->schema([
                        TextInput::make('title')->label('Tên dự án')->required()->maxLength(255)->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, Get $get, ?Project $record, ?string $state): void {
                                if (! $record && ! $get('slug_manually_edited')) {
                                    $set('slug', Str::slug($state ?? ''));
                                }
                            }),
                        Hidden::make('slug_manually_edited')->default(false)->dehydrated(false),
                        PermalinkInput::make('slug')->prefix(url('/du-an').'/')->required()->unique(ignoreRecord: true)
                            ->afterStateUpdated(fn (Set $set) => $set('slug_manually_edited', true)),
                        Group::make([
                            TextInput::make('code')->label('Mã dự án')->maxLength(50)->unique(ignoreRecord: true)->placeholder('DA-007'),
                            Select::make('project_category_id')->label('Nhóm dự án')->relationship('category', 'name')
                                ->required()->searchable()->preload()
                                ->createOptionForm([
                                    TextInput::make('name')->label('Tên nhóm')->required()->maxLength(255),
                                    TextInput::make('slug')->label('Mã lọc')->required()->alphaDash()->maxLength(255),
                                ])
                                ->helperText('Hiển thị thành tab lọc ở trang Dự án.'),
                        ])->columns(2),
                        Textarea::make('excerpt')->label('Mô tả ngắn')->rows(3)->required(),
                    ])->columns(1),
                    Section::make('Câu chuyện dự án')->schema([
                        Textarea::make('challenge')->label('Bài toán ban đầu')->rows(4),
                        Textarea::make('solution')->label('Giải pháp triển khai')->rows(4),
                        Repeater::make('results')->label('Kết quả đạt được')
                            ->simple(TextInput::make('value')->required()->maxLength(255))
                            ->addActionLabel('Thêm kết quả')->reorderable()->defaultItems(0),
                        TagsInput::make('deliverables')->label('Hạng mục bàn giao')->placeholder('Trang chủ, Sản phẩm…'),
                        TagsInput::make('technologies')->label('Công nghệ')->placeholder('Laravel, Tailwind CSS…'),
                    ])->columns(1),
                    Section::make('SEO')->collapsed()->schema([
                        TextInput::make('meta_title')->label('Tiêu đề SEO')->maxLength(255)
                            ->helperText('Bỏ trống sẽ dùng "Tên dự án | Dự án Tên website".'),
                        Textarea::make('meta_description')->label('Mô tả SEO')->rows(3)
                            ->helperText('Bỏ trống sẽ dùng mô tả ngắn.'),
                    ])->columns(1),
                ])->columnSpan(['default' => 1, 'lg' => 2]),
                Group::make([
                    Section::make('Thông tin khách hàng')->schema([
                        TextInput::make('client')->label('Khách hàng')->maxLength(255),
                        TextInput::make('industry')->label('Ngành')->maxLength(255)->placeholder('Sản xuất'),
                        TextInput::make('website_type')->label('Loại dự án')->maxLength(255),
                        TextInput::make('duration')->label('Thời gian thực hiện')->maxLength(100)->placeholder('4 tuần'),
                        TextInput::make('year')->label('Năm')->numeric()->minValue(2000)->maxValue(2100)->default(now()->year),
                        TextInput::make('link')->label('Link website')->url()->maxLength(255),
                    ])->columns(1),
                    Section::make('Hình ảnh')->schema([
                        CuratorPicker::make('image_id')->label('Ảnh đại diện')->constrained(),
                        CuratorPicker::make('gallery')->label('Bộ ảnh dự án')->multiple()->constrained()
                            ->helperText('Ảnh đầu tiên là ảnh lớn ở trang chi tiết.'),
                    ])->columns(1),
                    Section::make('Hiển thị')->schema([
                        Toggle::make('is_featured')->label('Dự án nổi bật'),
                        Toggle::make('is_active')->label('Đang hiển thị')->default(true),
                        TextInput::make('order')->label('Thứ tự')->numeric()->default(0),
                    ])->columns(1),
                ])->columnSpan(['default' => 1, 'lg' => 1]),
            ]);
    }
}
