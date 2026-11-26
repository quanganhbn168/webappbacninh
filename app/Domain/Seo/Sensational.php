<?php

namespace App\Domain\Seo;

/**
 * Clickbait, which Google's Discover and helpful content pages ask to avoid. Google gives no
 * word list; this one covers the usual Vietnamese phrasings.
 */
final class Sensational
{
    private const PHRASES = [
        'sốc', 'choáng váng', 'kinh hoàng', 'chấn động', 'rúng động', 'không thể tin', 'khó tin', 'không ngờ tới',
        'bạn sẽ không ngờ', 'sự thật đằng sau', 'xem ngay kẻo', 'đừng bỏ lỡ', 'cười ra nước mắt', 'ai cũng phải',
    ];

    public static function in(string $text): bool
    {
        if (collect(self::PHRASES)->contains(fn (string $phrase): bool => SeoText::contains($text, $phrase))) {
            return true;
        }

        $letters = preg_match_all('/\p{L}/u', $text);

        // Shouting: repeated marks, or mostly capital letters.
        return preg_match('/[!?]{2,}/', $text) === 1 || ($letters >= 15 && preg_match_all('/\p{Lu}/u', $text) / $letters > 0.6);
    }
}
