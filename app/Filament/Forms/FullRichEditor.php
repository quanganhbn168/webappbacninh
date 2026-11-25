<?php

namespace App\Filament\Forms;

use Awcodes\Curator\Components\Forms\RichEditor\AttachCuratorMediaPlugin;
use Filament\Forms\Components\RichEditor;

/**
 * RichEditor with every built-in tool switched on. Images are picked from the
 * Curator library (attachCuratorMedia) instead of Filament's own attachFiles.
 */
class FullRichEditor extends RichEditor
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->plugins([AttachCuratorMediaPlugin::make()])
            ->toolbarButtons([
                ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                ['textColor', 'highlight', 'small', 'lead', 'code', 'clearFormatting'],
                ['paragraph', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
                ['alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
                ['blockquote', 'codeBlock', 'bulletList', 'orderedList', 'horizontalRule'],
                ['table', 'grid', 'gridDelete', 'details', 'attachCuratorMedia'],
                ['undo', 'redo'],
            ])
            ->customTextColors()
            ->resizableImages()
            // Scrolls long content under a visible toolbar (resources/css/filament/admin/theme.css).
            ->extraAttributes(['class' => 'blog-content-editor']);
    }
}
