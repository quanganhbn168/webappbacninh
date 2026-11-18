<?php

namespace App\Filament\Pages;

use App\Domain\Analytics\Period;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;

/**
 * Dashboard: GA4 traffic, Search Console and new contact requests for one period.
 * The widgets live in App\Filament\Widgets and read the period from the filter here.
 */
class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static ?string $title = 'Dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    public function filtersForm(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('days')->label('Khoảng thời gian')
                ->options(Period::OPTIONS)->default(28)->selectablePlaceholder(false)->native(false),
        ]);
    }

    public function getColumns(): int|array
    {
        return ['default' => 1, 'lg' => 2];
    }
}
