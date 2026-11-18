<?php

namespace App\Filament\Resources\Menus;

use App\Filament\NavigationGroup;
use App\Filament\Resources\Menus\Pages\ManageMenus;
use App\Models\Menu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * The header and footer menus. Locations are fixed by the site layout, so
 * menus are edited in one builder page, never created or deleted.
 */
class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Website;

    protected static ?string $navigationLabel = 'Menu';

    protected static ?string $modelLabel = 'menu';

    protected static ?string $pluralModelLabel = 'Menu';

    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'name';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageMenus::route('/'),
        ];
    }
}
