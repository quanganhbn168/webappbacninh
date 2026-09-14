<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\RichEditor;

class BlogRichEditor extends RichEditor
{
    public function getOriginalFileAttachmentPaths(): array
    {
        // Legacy sections contain plain text, not a TipTap document or image attachments.
        $content = $this->getRecord()?->getOriginal($this->getName());
        $sections = is_string($content) ? json_decode($content, true) : null;
        if (is_array($sections) && array_is_list($sections)) {
            return [];
        }

        return parent::getOriginalFileAttachmentPaths();
    }
}
