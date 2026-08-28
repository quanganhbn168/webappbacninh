<?php

namespace App\Models;

use App\Support\FrontendMenuCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'route_name',
        'route_parameter',
        'url',
        'icon',
        'target',
        'position',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'position' => 'integer',
        ];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('position');
    }

    protected static function booted(): void
    {
        static::saved(function (MenuItem $item): void {
            $item->forgetMenuCache((int) $item->getOriginal('menu_id'));
            $item->forgetMenuCache((int) $item->menu_id);
        });

        static::deleted(fn (MenuItem $item) => $item->forgetMenuCache((int) $item->getOriginal('menu_id')));
    }

    private function forgetMenuCache(int $menuId): void
    {
        if ($menuId <= 0) {
            return;
        }

        $location = (string) DB::table('menus')->where('id', $menuId)->value('location');
        app(FrontendMenuCache::class)->forget($location);
    }
}
