<?php

namespace App\Filament\Resources\Technologies;

use App\Filament\Forms\IconPicker;
use App\Filament\NavigationGroup;
use App\Filament\Resources\Technologies\Pages\ManageTechnologies;
use App\Models\Technology;
use App\Support\Icons;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class TechnologyResource extends Resource
{
    protected static ?string $model = Technology::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCpuChip;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Portfolio;

    protected static ?string $navigationLabel = 'Công nghệ';

    protected static ?string $modelLabel = 'công nghệ';

    protected static ?string $pluralModelLabel = 'Công nghệ';

    protected static ?int $navigationSort = 25;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Tên')->required()->maxLength(255)->unique(ignoreRecord: true),
            IconPicker::make('icon')->label('Logo có sẵn')
                // Brand logos first, they are what technologies usually need.
                ->options(fn (): array => collect(Icons::options())
                    ->sortBy(fn (string $label, string $name): int => str_starts_with($name, 'brand-') ? 0 : 1)->all()),
            ColorPicker::make('color')->label('Màu logo có sẵn')->regex('/^#[0-9a-fA-F]{3,8}$/'),
            CuratorPicker::make('image_id')->label('Logo tải lên')->constrained()
                ->helperText('Nếu chọn, ảnh này thay cho logo có sẵn.'),
            TextInput::make('order')->label('Thứ tự')->integer()->minValue(0)
                ->helperText('Số nhỏ hiện trước. Bỏ trống khi tạo mới sẽ xếp cuối.')
                ->dehydrated(fn (?string $state): bool => filled($state)),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('order')
            ->defaultPaginationPageOption(50)
            ->modifyQueryUsing(fn ($query) => $query->with('image'))
            ->columns([
                TextColumn::make('logo')->label('Logo')->html()
                    ->state(fn (Technology $record): string => (string) $record->logoHtml('1.75rem')),
                TextColumn::make('name')->label('Tên')->searchable()->sortable(),
                TextColumn::make('projects_count')->label('Số dự án')->counts('projects')->sortable(),
                TextColumn::make('order')->label('Thứ tự')->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTechnologies::route('/'),
        ];
    }
}
