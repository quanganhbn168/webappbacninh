<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Illuminate\Support\Facades\DB;

/** The pages contact requests were sent from, in the GA4 period: which content brings customers. */
class LeadSources extends AnalyticsTable
{
    protected static ?int $sort = 8;

    protected function heading(): string
    {
        return 'Trang sinh ra liên hệ';
    }

    protected function source(): string
    {
        return 'Form liên hệ trên website';
    }

    protected function columns(): array
    {
        return ['Trang', 'Liên hệ'];
    }

    protected function rows(): array
    {
        $period = $this->ga4Period();
        $rows = Lead::query()
            ->whereBetween('created_at', [$period->start->startOfDay(), $period->end->endOfDay()])
            ->select('source', DB::raw('count(*) as total'))
            ->groupBy('source')->orderByDesc('total')->limit(10)
            ->get()->map(fn (Lead $lead): array => ['label' => (string) $lead->source, 'total' => (int) $lead->total])->all();
        $bars = self::bars($rows, 'total');

        return array_map(fn (array $row, float $bar): array => [
            'cells' => [$row['label'] !== '' ? $row['label'] : 'Không rõ trang', self::number($row['total'])],
            'href' => str_starts_with($row['label'], '/') ? url($row['label']) : null,
            'bar' => $bar,
        ], $rows, $bars);
    }
}
