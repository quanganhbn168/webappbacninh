<?php

namespace App\Filament\Resources\ProjectCategories;

use App\Filament\Resources\ProjectCategories\Pages\ManageProjectCategories;
use App\Models\ProjectCategory;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class ProjectCategoryResource extends Resource
{
    protected static ?string $model = ProjectCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static string|UnitEnum|null $navigationGroup = 'Nội dung';

    protected static ?string $navigationLabel = 'Nhóm dự án';

    protected static ?string $modelLabel = 'nhóm dự án';

    protected static ?string $pluralModelLabel = 'Nhóm dự án';

    protected static ?int $navigationSort = 11;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Tên')->required()->maxLength(255)->live(onBlur: true)
                ->afterStateUpdated(fn ($set, $get, ?string $state) => blank($get('slug')) ? $set('slug', Str::slug($state ?? '')) : null),
            TextInput::make('slug')->label('Mã lọc')->required()->maxLength(255)->alphaDash()->unique(ignoreRecord: true)
                ->helperText('Chữ thường không dấu, dùng trong bộ lọc của trang Nội dung.'),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Tên')->searchable()->sortable(),
                TextColumn::make('slug')->label('Mã lọc')->searchable(),
                TextColumn::make('projects_count')->label('Số dự án')->counts('projects')->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageProjectCategories::route('/'),
        ];
    }
}
