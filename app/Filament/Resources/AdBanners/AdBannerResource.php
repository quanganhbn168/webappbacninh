<?php

namespace App\Filament\Resources\AdBanners;

use App\Filament\Resources\AdBanners\Pages\CreateAdBanner;
use App\Filament\Resources\AdBanners\Pages\EditAdBanner;
use App\Filament\Resources\AdBanners\Pages\ListAdBanners;
use App\Filament\Resources\AdBanners\Schemas\AdBannerForm;
use App\Filament\Resources\AdBanners\Tables\AdBannersTable;
use App\Models\AdBanner;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AdBannerResource extends Resource
{
    protected static ?string $model = AdBanner::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|UnitEnum|null $navigationGroup = 'Nội dung';

    protected static ?string $navigationLabel = 'Banner quảng cáo';

    protected static ?string $modelLabel = 'banner';

    protected static ?string $pluralModelLabel = 'Banner quảng cáo';

    protected static ?int $navigationSort = 16;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return AdBannerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdBannersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdBanners::route('/'),
            'create' => CreateAdBanner::route('/create'),
            'edit' => EditAdBanner::route('/{record}/edit'),
        ];
    }
}
