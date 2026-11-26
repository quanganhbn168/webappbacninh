<?php

namespace App\Domain\Seo;

use Normalizer;

/**
 * Text comparison for Vietnamese: case and punctuation are ignored, accents are not.
 * A "word" is a syllable, the same unit the reading time is counted in.
 */
final class SeoText
{
    public static function normalize(string $text): string
    {
        $text = Normalizer::normalize($text, Normalizer::FORM_C) ?: $text;

        return trim((string) preg_replace('/[^\p{L}\p{M}\p{N}]+/u', ' ', mb_strtolower($text)));
    }

    /** @return array<int, string> */
    public static function words(string $text): array
    {
        $text = self::normalize($text);

        return $text === '' ? [] : explode(' ', $text);
    }

    public static function countWords(string $text): int
    {
        return count(self::words($text));
    }

    /** How many times the phrase appears as whole words. */
    public static function occurrences(string $text, string $phrase): int
    {
        $phrase = self::normalize($phrase);

        return $phrase === '' ? 0 : (int) preg_match_all('/(?<= )'.preg_quote($phrase, '/').'(?= )/u', ' '.self::normalize($text).' ');
    }

    public static function contains(string $text, string $phrase): bool
    {
        return self::occurrences($text, $phrase) > 0;
    }
}
