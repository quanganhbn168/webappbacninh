<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Domain\Projects\Actions\GenerateProjectCode;
use App\Filament\Forms\FullRichEditor;
use App\Filament\Forms\IconPicker;
use App\Filament\Forms\PermalinkInput;
use App\Filament\Forms\SeoTitleInput;
use App\Models\Project;
use App\Models\Technology;
use App\Models\TemplateCategory;
use App\Settings\ProjectSettings;
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
                            TextInput::make('code')->label('Mã dự án')->maxLength(50)->unique(ignoreRecord: true)
                                ->placeholder(fn (): string => 'Tự sinh: '.app(GenerateProjectCode::class)->preview())
                                ->helperText('Bỏ trống để tự sinh. Đổi tiền tố, hậu tố và số tiếp theo ở "Cấu hình dự án" trên trang danh sách.'),
                            Select::make('project_category_id')->label('Nhóm dự án')->relationship('category', 'name')
                                ->required()->searchable()->preload()
                                ->createOptionForm([
                                    TextInput::make('name')->label('Tên nhóm')->required()->maxLength(255),
                                    TextInput::make('slug')->label('Mã lọc')->required()->alphaDash()->maxLength(255),
                                ])
                                ->helperText('Tab lọc ở trang Dự án, đồng thời là "Loại dự án" ở trang chi tiết.'),
                        ])->columns(2),
                        Textarea::make('excerpt')->label('Mô tả ngắn')->rows(3)->required(),
                    ])->columns(1),
                    Section::make('Bộ ảnh dự án')->schema([
                        // Not constrained(): the tiles use Curator's square crop of each image.
                        CuratorPicker::make('gallery')->hiddenLabel()->multiple()->buttonLabel('Thêm ảnh')
                            ->extraAttributes(['class' => 'square-gallery-item'])
                            ->helperText('Nên dùng ảnh rộng từ 1600px. Kéo biểu tượng mũi tên để sắp xếp, ảnh số 1 mở đầu slider ở trang chi tiết.'),
                    ])->columns(1),
                    Section::make('Câu chuyện dự án')->schema([
                        FullRichEditor::make('challenge')->label('Bài toán ban đầu'),
                        FullRichEditor::make('solution')->label('Giải pháp triển khai'),
                        Repeater::make('results')->label('Kết quả đạt được')
                            ->simple(TextInput::make('value')->required()->maxLength(255))
                            ->addActionLabel('Thêm kết quả')->reorderable()->defaultItems(0),
                    ])->columns(1),
                    Section::make('Phạm vi và công nghệ')->schema([
                        ...self::deliverables(),
                        self::technologies(),
                    ])->columns(1),
                    Section::make('SEO')->collapsed()->schema([
                        SeoTitleInput::make('meta_title'),
                        Textarea::make('meta_description')->label('Mô tả SEO')->rows(3)
                            ->helperText('Bỏ trống sẽ dùng mô tả ngắn.'),
                    ])->columns(1),
                ])->columnSpan(['default' => 1, 'lg' => 2]),
                Group::make([
                    Section::make('Thông tin khách hàng')->schema([
                        TextInput::make('client')->label('Khách hàng')->maxLength(255),
                        Select::make('industries')->label('Ngành')->relationship('industries', 'name')
                            ->multiple()->searchable()->preload()
                            ->createOptionForm([TextInput::make('name')->label('Tên ngành')->required()->maxLength(255)])
                            ->createOptionUsing(fn (array $data): int => TemplateCategory::findOrCreateByName($data['name'])->id)
                            ->helperText('Chọn được nhiều ngành, dùng chung với Kho giao diện.'),
                        TextInput::make('duration')->label('Thời gian thực hiện')->maxLength(100)->placeholder('4 tuần'),
                        TextInput::make('cost')->label('Chi phí tham khảo')->maxLength(255)->placeholder('Từ 25 triệu')
                            ->helperText('Chữ tự do, ví dụ "Từ 25 triệu" hoặc "Liên hệ báo giá". Bỏ trống sẽ ẩn.'),
                        TextInput::make('year')->label('Năm')->numeric()->minValue(2000)->maxValue(2100)->default(now()->year),
                        TextInput::make('link')->label('Link website')->url()->maxLength(255),
                    ])->columns(1),
                    Section::make('Ảnh đại diện')->schema([
                        CuratorPicker::make('image_id')->hiddenLabel()->constrained(),
                    ])->columns(1),
                    Section::make('Hiển thị')->schema([
                        Toggle::make('is_featured')->label('Dự án nổi bật'),
                        Toggle::make('is_active')->label('Đang hiển thị')->default(true),
                        TextInput::make('order')->label('Thứ tự')->numeric()->default(0),
                    ])->columns(1),
                ])->columnSpan(['default' => 1, 'lg' => 1]),
            ]);
    }

    /**
     * Fixed deliverables are ticked, anything else is typed; both are saved
     * together in `deliverables`, the fixed ones first.
     *
     * @return array<int, Hidden|CheckboxList|TagsInput>
     */
    private static function deliverables(): array
    {
        $presets = fn (): array => app(ProjectSettings::class)->deliverable_presets;

        return [
            Hidden::make('deliverables')->dehydrateStateUsing(fn (Get $get): array => array_values(array_unique([
                ...array_intersect($presets(), $get('deliverable_presets') ?? []),
                ...($get('deliverable_extra') ?? []),
            ]))),
            CheckboxList::make('deliverable_presets')->label('Hạng mục bàn giao')
                ->options(fn (): array => array_combine($presets(), $presets()))
                ->columns(['default' => 1, 'md' => 3])
                ->bulkToggleable()
                ->afterStateHydrated(fn (CheckboxList $component, ?Project $record) => $component->state(
                    array_values(array_intersect($presets(), $record?->deliverables ?? [])),
                ))
                ->dehydrated(false),
            TagsInput::make('deliverable_extra')->label('Hạng mục khác')->placeholder('Gõ rồi nhấn Enter')
                ->afterStateHydrated(fn (TagsInput $component, ?Project $record) => $component->state(
                    array_values(array_diff($record?->deliverables ?? [], $presets())),
                ))
                ->dehydrated(false),
        ];
    }

    private static function technologies(): Select
    {
        return Select::make('technologies')->label('Công nghệ')->relationship('technologies', 'name')
            ->multiple()->searchable()->preload()
            ->allowHtml()
            ->getOptionLabelFromRecordUsing(fn (Technology $record): string => '<span style="display:inline-flex;gap:.4rem;align-items:center">'
                .$record->logoHtml('1rem').'<span>'.e($record->name).'</span></span>')
            ->createOptionForm([
                TextInput::make('name')->label('Tên công nghệ')->required()->maxLength(255),
                IconPicker::make('icon')->label('Logo có sẵn'),
            ])
            ->createOptionUsing(function (array $data): int {
                $technology = Technology::findOrCreateByName($data['name']);

                if ($technology->wasRecentlyCreated) {
                    $technology->update(['icon' => $data['icon'] ?? null]);
                }

                return $technology->id;
            })
            ->helperText('Muốn tải logo riêng hoặc đổi màu: vào menu "Công nghệ".');
    }
}
