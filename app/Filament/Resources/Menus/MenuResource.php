<?php

namespace App\Filament\Resources\Menus;

use App\Filament\Forms\IconPicker;
use App\Filament\Resources\Menus\Pages\EditMenu;
use App\Filament\Resources\Menus\Pages\ListMenus;
use App\Models\Menu;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

/**
 * The header and footer menus. Locations are fixed by the site layout, so
 * menus are edited, not created or deleted.
 */
class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3;

    protected static string|UnitEnum|null $navigationGroup = 'Nội dung';

    protected static ?string $navigationLabel = 'Menu';

    protected static ?string $modelLabel = 'menu';

    protected static ?string $pluralModelLabel = 'Menu';

    protected static ?int $navigationSort = 6;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Thiết lập')->schema([
                TextInput::make('name')->label('Tên menu')->required()->maxLength(255)
                    ->helperText('Với menu ở footer, tên này là tiêu đề của cột.'),
                Toggle::make('is_active')->label('Đang hiển thị'),
            ])->columns(2),
            Section::make('Các mục menu')->schema([
                Repeater::make('items')->hiddenLabel()
                    ->relationship('items')
                    ->orderColumn('position')
                    ->schema([
                        ...self::itemFields(),
                        Repeater::make('children')->label('Menu con')
                            ->relationship('children')
                            ->orderColumn('position')
                            ->schema(self::itemFields(withIcon: true))
                            ->columns(2)->collapsible()->collapsed()->defaultItems(0)
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->addActionLabel('Thêm menu con')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)->collapsible()->collapsed()->defaultItems(0)
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->addActionLabel('Thêm mục menu'),
            ]),
        ]);
    }

    /**
     * @return array<int, Component>
     */
    private static function itemFields(bool $withIcon = false): array
    {
        return array_values(array_filter([
            TextInput::make('title')->label('Tên hiển thị')->required()->maxLength(255),
            TextInput::make('url')->label('Đường dẫn')->required()->maxLength(2048)
                ->placeholder('/lien-he hoặc https://…')
                ->helperText('Trang trong website nhập dạng /duong-dan, có thể thêm #muc.'),
            $withIcon ? IconPicker::make('icon')->helperText('Hiện trong menu con của header.') : null,
            Toggle::make('open_in_new_tab')->label('Mở tab mới'),
            Toggle::make('is_active')->label('Hiển thị')->default(true),
        ]));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Tên menu'),
                TextColumn::make('location')->label('Vị trí')
                    ->formatStateUsing(fn (string $state): string => Menu::locations()[$state] ?? $state),
                TextColumn::make('all_items_count')->label('Số mục')->counts('allItems'),
                IconColumn::make('is_active')->label('Hiển thị')->boolean(),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}
