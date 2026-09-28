<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Font Awesome class names stored by the admin ("fa-solid fa-server") become
 * names from the site icon set ("server"), see App\Support\Icons.
 */
return new class extends Migration
{
    /** @var array<string, array<int, string>> */
    private array $plain = [
        'menu_items' => ['icon'],
        'mini_apps' => ['icon'],
        'services' => ['icon'],
        'operation_services' => ['icon'],
        'pages' => ['icon'],
    ];

    /** @var array<string, array<int, string>> JSON columns holding cards with an "icon" key. */
    private array $json = [
        'services' => ['problems', 'features', 'benefits', 'process', 'scope'],
        'operation_services' => ['problems', 'features', 'benefits', 'process', 'scope'],
    ];

    public function up(): void
    {
        $map = require database_path('data/legacy-icon-map.php');
        $convert = function (?string $value) use ($map): ?string {
            if ($value === null || ! str_contains($value, 'fa-')) {
                return $value;
            }
            preg_match('/fa-(?!solid|regular|brands)([a-z0-9-]+)/', $value, $match);

            return $map[$match[1] ?? ''] ?? null;
        };

        foreach ($this->plain as $table => $columns) {
            foreach (array_filter($columns, fn (string $column): bool => Schema::hasTable($table) && Schema::hasColumn($table, $column)) as $column) {
                if ($column === 'icon' && $table === 'mini_apps') {
                    Schema::table($table, fn ($blueprint) => $blueprint->string('icon')->nullable()->default(null)->change());
                }
                DB::table($table)->where($column, 'like', '%fa-%')->get(['id', $column])
                    ->each(fn (object $row) => DB::table($table)->where('id', $row->id)->update([$column => $convert($row->{$column})]));
            }
        }

        foreach ($this->json as $table => $columns) {
            foreach (array_filter($columns, fn (string $column): bool => Schema::hasTable($table) && Schema::hasColumn($table, $column)) as $column) {
                DB::table($table)->where($column, 'like', '%fa-%')->get(['id', $column])->each(function (object $row) use ($table, $column, $convert): void {
                    $items = json_decode((string) $row->{$column}, true);
                    if (! is_array($items)) {
                        return;
                    }
                    array_walk_recursive($items, function (mixed &$value, string|int $key) use ($convert): void {
                        if ($key === 'icon' && is_string($value)) {
                            $value = $convert($value);
                        }
                    });
                    DB::table($table)->where('id', $row->id)->update([$column => json_encode($items, JSON_UNESCAPED_UNICODE)]);
                });
            }
        }

        Cache::flush();
    }

    public function down(): void
    {
        // Icon names are not converted back to Font Awesome classes.
    }
};
