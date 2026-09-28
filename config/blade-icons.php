<?php

// Blade Icons powers Filament's icons through svg(). Its <x-icon> component is disabled
// because the public site has its own <x-icon> (resources/views/components/icon.blade.php).
return [
    'sets' => [],
    'class' => '',
    'attributes' => [],
    'fallback' => '',
    'components' => [
        'disabled' => true,
        'default' => null,
    ],
];
