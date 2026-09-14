<?php

namespace App\Filament\Resources\PostCategories\Pages;

use App\Filament\Resources\PostCategories\PostCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePostCategory extends CreateRecord
{
    protected ?bool $hasDatabaseTransactions = true;

    protected static string $resource = PostCategoryResource::class;
}
