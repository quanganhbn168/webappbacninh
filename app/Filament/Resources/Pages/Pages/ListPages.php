<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Resources\Pages\PageResource;
use Filament\Resources\Pages\ListRecords;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    public function getSubheading(): ?string
    {
        return 'Nội dung mỗi trang nằm trong giao diện; ở đây chỉnh tiêu đề SEO, mô tả, ảnh chia sẻ và banner đầu trang.';
    }
}
