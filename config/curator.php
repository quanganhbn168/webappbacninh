<?php

return [
    'default_disk' => 'public',
    'default_directory' => 'media',
    'default_visibility' => 'public',

    // Repeated in full because the package merges its config one level deep only.
    // Label, group and sort are set on CuratorPlugin in AdminPanelProvider.
    'resource' => [
        'label' => 'Tệp',
        'plural_label' => 'Thư viện ảnh',
        'default_layout' => 'grid',
        'navigation' => [
            'group' => null,
            'icon' => 'heroicon-o-photo',
            'sort' => null,
            'should_register' => true,
            'should_show_badge' => false,
        ],
        'resource' => App\Filament\Curator\MediaResource::class,
        'pages' => [
            'create' => App\Filament\Curator\Pages\CreateMedia::class,
            'edit' => App\Filament\Curator\Pages\EditMedia::class,
            'index' => App\Filament\Curator\Pages\ListMedia::class,
        ],
        'schemas' => [
            'form' => Awcodes\Curator\Resources\Media\Schemas\MediaForm::class,
        ],
        'tables' => [
            'table' => Awcodes\Curator\Resources\Media\Tables\MediaTable::class,
        ],
    ],
];
