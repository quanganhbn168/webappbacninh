<?php

namespace App\Filament\Curator\Pages;

use App\Filament\Curator\MediaResource;
use Awcodes\Curator\Resources\Media\Pages\ListMedia as CuratorListMedia;

class ListMedia extends CuratorListMedia
{
    protected static string $resource = MediaResource::class;

    public function getTitle(): string
    {
        return MediaResource::getPluralModelLabel();
    }
}
