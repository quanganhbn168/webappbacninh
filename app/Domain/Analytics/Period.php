<?php

namespace App\Domain\Analytics;

use Carbon\CarbonImmutable;

/**
 * The dashboard period and the one before it, of equal length, for comparisons.
 * GA4 counts up to yesterday (today is still incomplete); Search Console data
 * arrives with a delay of about two days, so its window ends three days ago.
 */
final readonly class Period
{
    public const OPTIONS = [7 => '7 ngày qua', 28 => '28 ngày qua', 90 => '90 ngày qua'];

    private function __construct(
        public int $days,
        public CarbonImmutable $start,
        public CarbonImmutable $end,
    ) {}

    public static function lastDays(int $days, int $lagDays = 1): self
    {
        $days = array_key_exists($days, self::OPTIONS) ? $days : 28;
        $end = CarbonImmutable::today()->subDays($lagDays);

        return new self($days, $end->subDays($days - 1), $end);
    }

    public static function forGa4(int $days): self
    {
        return self::lastDays($days, 1);
    }

    public static function forSearchConsole(int $days): self
    {
        return self::lastDays($days, 3);
    }

    public function previous(): self
    {
        return new self($this->days, $this->start->subDays($this->days), $this->start->subDay());
    }

    /** @return array{startDate: string, endDate: string} */
    public function range(): array
    {
        return ['startDate' => $this->start->toDateString(), 'endDate' => $this->end->toDateString()];
    }

    public function label(): string
    {
        return $this->start->format('d/m').' – '.$this->end->format('d/m/Y');
    }

    public function key(): string
    {
        return $this->start->toDateString().'_'.$this->end->toDateString();
    }
}
