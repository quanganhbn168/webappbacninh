<?php

namespace App\Support;

use Illuminate\Support\HtmlString;

/**
 * The site's SVG icon set (resources/icons/icons.json, built by scripts/build-icons.mjs).
 * Outline icons come from Lucide, brand logos (prefixed "brand-") from Simple Icons.
 */
final class Icons
{
    /** @var array<string, array{body: string, brand?: bool}>|null */
    private static ?array $icons = null;

    /**
     * @return array<string, array{body: string, brand?: bool}>
     */
    public static function all(): array
    {
        return self::$icons ??= json_decode((string) file_get_contents(resource_path('icons/icons.json')), true, 512, JSON_THROW_ON_ERROR);
    }

    public static function has(?string $name): bool
    {
        return $name !== null && isset(self::all()[$name]);
    }

    /**
     * Inline SVG markup. Decorative unless a label is given; unknown names render nothing.
     */
    public static function svg(?string $name, string $class = '', ?string $label = null): HtmlString
    {
        $icon = $name !== null ? (self::all()[$name] ?? null) : null;
        if ($icon === null) {
            return new HtmlString('');
        }

        $paint = ($icon['brand'] ?? false)
            ? 'fill="currentColor"'
            : 'fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"';
        $a11y = $label !== null ? 'role="img" aria-label="'.e($label).'"' : 'aria-hidden="true" focusable="false"';

        return new HtmlString(sprintf(
            '<svg class="%s" width="24" height="24" viewBox="0 0 24 24" %s %s>%s</svg>',
            e(trim('icon '.$class)),
            $paint,
            $a11y,
            $icon['body'],
        ));
    }

    /**
     * Options for admin icon pickers: name => label with a preview.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::all())
            ->mapWithKeys(fn (array $icon, string $name): array => [$name => '<span style="display:inline-flex;gap:.5rem;align-items:center">'.str_replace('<svg ', '<svg style="width:1.25rem;height:1.25rem" ', (string) self::svg($name)).'<span>'.e($name).'</span></span>'])
            ->all();
    }
}
