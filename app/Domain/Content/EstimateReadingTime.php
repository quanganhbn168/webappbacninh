<?php

namespace App\Domain\Content;

class EstimateReadingTime
{
    public function execute(?string $content): int
    {
        $html = app(PrepareBlogContent::class)->execute($content);
        $html = preg_replace('~<(script|style)\b[^>]*>.*?</\1>~is', ' ', $html) ?? '';
        $text = html_entity_decode(preg_replace('/<[^>]+>/', ' ', $html) ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $words = preg_match_all('/[\p{L}\p{N}]+/u', $text);

        return max(1, (int) ceil($words / 200));
    }
}
