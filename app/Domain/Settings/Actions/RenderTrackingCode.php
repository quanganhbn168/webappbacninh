<?php

namespace App\Domain\Settings\Actions;

use App\Domain\Settings\Data\TrackingCodeData;
use App\Settings\TrackingSettings;

final class RenderTrackingCode
{
    public function __construct(private readonly TrackingSettings $settings) {}

    public function execute(): TrackingCodeData
    {
        // A signed-in admin checking the site is not a visitor: keep them out of GA4 and the pixels.
        if (! $this->settings->enabled || auth('admin')->check()) {
            return new TrackingCodeData('', '', '');
        }

        // Google Analytics and any other tag are pasted as-is into the head code.
        return new TrackingCodeData(
            head: trim($this->settings->head_code),
            bodyStart: trim($this->settings->body_start_code),
            bodyEnd: trim($this->settings->body_end_code),
        );
    }
}
