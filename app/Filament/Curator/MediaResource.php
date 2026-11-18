<?php

namespace App\Filament\Curator;

use App\Filament\Curator\Pages\CreateMedia;
use App\Filament\Curator\Pages\EditMedia;
use App\Filament\Curator\Pages\ListMedia;
use Awcodes\Curator\Resources\Media\MediaResource as CuratorMediaResource;

/**
 * Curator's media library, registered through config('curator.resource.resource').
 * Curator title-cases its labels ("Thư Viện Ảnh"); keep the label as written.
 */
class MediaResource extends CuratorMediaResource
{
    public static function getNavigationLabel(): string
    {
        return static::getPluralModelLabel();
    }
    public static function getPages(): array
    {
        return [
            'index' => ListMedia::route('/'),
            'create' => CreateMedia::route('/create'),
            'edit' => EditMedia::route('/{record}/edit'),
        ];
    }
}
