<?php

namespace App\Filament\Resources\MiniApps;

use App\Filament\Resources\MiniApps\Pages\CreateMiniApp;
use App\Filament\Resources\MiniApps\Pages\EditMiniApp;
use App\Filament\Resources\MiniApps\Pages\ListMiniApps;
use App\Filament\Resources\MiniApps\Schemas\MiniAppForm;
use App\Filament\Resources\MiniApps\Tables\MiniAppsTable;
use App\Models\MiniApp;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MiniAppResource extends Resource
{
    protected static ?string $model = MiniApp::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static string|UnitEnum|null $navigationGroup = 'Nội dung';

    protected static ?string $navigationLabel = 'Công cụ miễn phí';

    protected static ?string $modelLabel = 'công cụ';

    protected static ?string $pluralModelLabel = 'Công cụ miễn phí';

    protected static ?int $navigationSort = 15;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return MiniAppForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MiniAppsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMiniApps::route('/'),
            'create' => CreateMiniApp::route('/create'),
            'edit' => EditMiniApp::route('/{record}/edit'),
        ];
    }
}
