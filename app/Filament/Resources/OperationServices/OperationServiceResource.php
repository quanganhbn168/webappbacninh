<?php

namespace App\Filament\Resources\OperationServices;

use App\Filament\Resources\OperationServices\Pages\CreateOperationService;
use App\Filament\Resources\OperationServices\Pages\EditOperationService;
use App\Filament\Resources\OperationServices\Pages\ListOperationServices;
use App\Filament\Resources\OperationServices\Schemas\OperationServiceForm;
use App\Filament\Resources\OperationServices\Tables\OperationServicesTable;
use App\Models\OperationService;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class OperationServiceResource extends Resource
{
    protected static ?string $model = OperationService::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Nội dung';

    protected static ?string $navigationLabel = 'Dịch vụ vận hành';

    protected static ?string $modelLabel = 'dịch vụ vận hành';

    protected static ?string $pluralModelLabel = 'Dịch vụ vận hành';

    protected static ?int $navigationSort = 30;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return OperationServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OperationServicesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOperationServices::route('/'),
            'create' => CreateOperationService::route('/create'),
            'edit' => EditOperationService::route('/{record}/edit'),
        ];
    }
}
