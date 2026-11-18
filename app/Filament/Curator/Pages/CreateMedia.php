<?php

namespace App\Filament\Curator\Pages;

use App\Filament\Curator\MediaResource;
use Awcodes\Curator\Resources\Media\Pages\CreateMedia as CuratorCreateMedia;

class CreateMedia extends CuratorCreateMedia
{
    protected static string $resource = MediaResource::class;
}
