<?php

namespace App\Domain\Seo;

/**
 * One rule's verdict. A scored rule rests on a Google document ($source) or reports something
 * broken; advice and information carry no weight.
 */
final readonly class SeoCheck
{
    public const PASS = 'pass';

    public const WARN = 'warn';

    public const FAIL = 'fail';

    public const TIP = 'tip';

    public const INFO = 'info';

    private function __construct(
        public string $key,
        public string $status,
        public string $message,
        public int $weight = 0,
        public bool $critical = false,
        public ?string $source = null,
        public bool $advice = false,
    ) {}

    /** A scored rule: it passes or does not; $otherwise says how bad not passing is. */
    public static function rule(string $key, bool $ok, string $passed, string $problem, ?string $source, int $weight = 1, string $otherwise = self::WARN, bool $critical = false): self
    {
        return new self($key, $ok ? self::PASS : $otherwise, $ok ? $passed : $problem, $weight, $critical, $source);
    }

    /** Good practice that Google does not ask for: shown apart, never scored. */
    public static function tip(string $key, bool $ok, string $passed, string $suggestion): self
    {
        return new self($key, $ok ? self::PASS : self::TIP, $ok ? $passed : $suggestion, advice: true);
    }

    public static function info(string $key, string $message, ?string $source = null): self
    {
        return new self($key, self::INFO, $message, source: $source);
    }
}
