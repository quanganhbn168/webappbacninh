<?php

namespace App\Filament\Widgets;

use App\Domain\Analytics\AnalyticsUnavailable;
use App\Filament\Widgets\Concerns\ReadsAnalytics;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

/**
 * A short ranked table (top pages, channels, queries) read from GA4 or Search Console.
 * Subclasses give the heading, the column titles and the rows.
 */
abstract class AnalyticsTable extends Widget
{
    use InteractsWithPageFilters;
    use ReadsAnalytics;

    protected string $view = 'filament.widgets.analytics-table';

    abstract protected function heading(): string;

    abstract protected function source(): string;

    /** @return array<int, string> column titles; the first is the label column */
    abstract protected function columns(): array;

    /**
     * @return array<int, array{cells: array<int, string>, href?: string|null, bar?: float}>
     *
     * @throws AnalyticsUnavailable
     */
    abstract protected function rows(): array;

    /** @return array<string, mixed> */
    protected function getViewData(): array
    {
        try {
            $rows = $this->rows();
            $error = null;
        } catch (AnalyticsUnavailable $e) {
            $rows = [];
            $error = $e->getMessage();
        }

        return [
            'heading' => $this->heading(),
            'source' => $this->source(),
            'columns' => $this->columns(),
            'rows' => $rows,
            'error' => $error,
        ];
    }

    /** Share of the largest value, for the bar behind each row. */
    protected static function bars(array $rows, string $key): array
    {
        $max = max(array_column($rows, $key) ?: [0]) ?: 1;

        return array_map(fn (array $row): float => $row[$key] / $max, $rows);
    }
}
