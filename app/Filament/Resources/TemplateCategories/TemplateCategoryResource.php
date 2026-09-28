<?php

namespace App\Filament\Resources\TemplateCategories;

use App\Filament\Resources\TemplateCategories\Pages\ManageTemplateCategories;
use App\Models\TemplateCategory;
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

class TemplateCategoryResource extends Resource
{
    protected static ?string $model = TemplateCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = 'Kho giao diện';

    protected static ?string $navigationLabel = 'Ngành giao diện';

    protected static ?string $modelLabel = 'ngành';

    protected static ?string $pluralModelLabel = 'Ngành giao diện';

    protected static ?int $navigationSort = 51;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Tên')->required()->maxLength(255)->live(onBlur: true)
                ->afterStateUpdated(fn ($set, $get, ?string $state) => blank($get('slug')) ? $set('slug', Str::slug($state ?? '')) : null),
            TextInput::make('slug')->label('Mã lọc')->required()->maxLength(255)->alphaDash()->unique(ignoreRecord: true)
                ->helperText('Chữ thường không dấu, dùng trong bộ lọc của trang Kho giao diện.'),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Tên')->searchable()->sortable(),
                TextColumn::make('slug')->label('Mã lọc')->searchable(),
                TextColumn::make('templates_count')->label('Số giao diện')->counts('templates')->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTemplateCategories::route('/'),
        ];
    }
}
