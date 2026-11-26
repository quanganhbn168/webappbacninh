<?php

namespace App\Filament\Forms;

use App\Domain\Content\Faqs;
use Filament\Schemas\Components\StateCasts\Contracts\StateCast;

/** Opens the stored [{q, a}] list in the editor as text; every other state passes through. */
class FaqStateCast implements StateCast
{
    public function get(mixed $state): mixed
    {
        return $state;
    }

    public function set(mixed $state): mixed
    {
        // An editor document is keyed ("type", "content"); a stored list is not.
        $state = is_array($state) && ! isset($state['type']) ? Faqs::toHtml(array_values($state)) : $state;

        // The editor cannot parse an empty string; "nothing" has to be null.
        return $state === '' ? null : $state;
    }
}
