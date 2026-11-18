<?php

namespace App\Filament\Curator\Pages;

use App\Filament\Curator\MediaResource;
use Awcodes\Curator\Resources\Media\Pages\EditMedia as CuratorEditMedia;

class EditMedia extends CuratorEditMedia
{
    protected static string $resource = MediaResource::class;
}
