<?php

namespace App\Traits;

use App\Domain\Content\Faqs;

/** Common questions kept in a "faqs" JSON column as [{q, a}]. The model casts it to an array. */
trait HasFaqs
{
    /**
     * The questions that are complete, shown on the page and in its FAQ schema.
     *
     * @return array<int, array{q: string, a: string}>
     */
    public function faqItems(): array
    {
        return array_values(array_filter($this->faqs ?? [], fn (array $faq): bool => filled($faq['q'] ?? null) && Faqs::plain((string) ($faq['a'] ?? '')) !== ''));
    }
}
