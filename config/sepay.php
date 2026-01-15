<?php

// Note: Database settings are read in SePayProvider constructor, not here
return [
    'webhook_token' => env('SEPAY_WEBHOOK_TOKEN'),
    'pattern' => env('SEPAY_MATCH_PATTERN', 'SE'),
];
