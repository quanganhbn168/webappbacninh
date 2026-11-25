<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * `challenge` and `solution` moved from textareas to the RichEditor. Wrap the
     * existing plain text in paragraphs so the editor keeps its line breaks.
     */
    public function up(): void
    {
        foreach (DB::table('projects')->get(['id', 'challenge', 'solution']) as $project) {
            $changes = [];

            foreach (['challenge', 'solution'] as $column) {
                $text = trim((string) $project->{$column});

                // Anything starting with a tag is already HTML.
                if ($text === '' || preg_match('/^<[a-z]/i', $text)) {
                    continue;
                }

                $changes[$column] = collect(preg_split('/\R\s*\R/u', $text))
                    ->map(fn (string $paragraph): string => '<p>'.preg_replace('/\R/u', '<br>', e(trim($paragraph), false)).'</p>')
                    ->implode('');
            }

            if ($changes !== []) {
                DB::table('projects')->where('id', $project->id)->update($changes);
            }
        }
    }

    public function down(): void
    {
        // The HTML stays: stripping it would lose formatting added since.
    }
};
