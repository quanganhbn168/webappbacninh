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

        $head = [];
        $googleTagId = trim($this->settings->google_tag_id);

        if ($this->isValidGoogleTagId($googleTagId)) {
            $encodedId = rawurlencode($googleTagId);
            $javascriptId = json_encode($googleTagId, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR);

            $head[] = '<script async src="https://www.googletagmanager.com/gtag/js?id='.$encodedId.'"></script>';
            $head[] = '<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag(\'js\',new Date());gtag(\'config\','.$javascriptId.');</script>';
        }

        if (trim($this->settings->head_code) !== '') {
            $head[] = trim($this->settings->head_code);
        }

        return new TrackingCodeData(
            head: implode(PHP_EOL, $head),
            bodyStart: trim($this->settings->body_start_code),
            bodyEnd: trim($this->settings->body_end_code),
        );
    }

    private function isValidGoogleTagId(string $id): bool
    {
        return (bool) preg_match('/^(G|GT|GTM|AW|DC)-[A-Z0-9-]+$/i', $id);
    }
}
