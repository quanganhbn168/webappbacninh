<?php

use App\Domain\Content\PrepareBlogContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Posts seeded from the old config stored their body as a JSON list of
 * sections. The editor and the public page now use HTML only.
 */
return new class extends Migration
{
    public function up(): void
    {
        $prepare = app(PrepareBlogContent::class);

        DB::table('posts')->orderBy('id')->get(['id', 'content'])->each(function (object $post) use ($prepare): void {
            $html = $prepare->execute($post->content);

            if ($html !== ($post->content ?? '')) {
                DB::table('posts')->where('id', $post->id)->update(['content' => $html]);
            }
        });
    }

    public function down(): void
    {
        //
    }
};
