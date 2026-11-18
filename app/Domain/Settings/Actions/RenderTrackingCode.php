<?php

namespace App\Domain\Settings\Actions;

use App\Domain\Settings\Data\TrackingCodeData;
use App\Settings\TrackingSettings;

final class RenderTrackingCode
{
    public function __construct(private readonly TrackingSettings $settings) {}

    public function execute(): TrackingCodeData
    {
        if (! $this->settings->enabled) {
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
