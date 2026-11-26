<?php

namespace App\Domain\Seo;

use App\Models\Post;

/** Keeps the score of a saved article for the article list; its editing date stays as it is. */
final class StoreSeoScore
{
    public function __construct(private readonly AnalyzePost $analyze) {}

    public function execute(Post $post): void
    {
        $post = $post->fresh();
        $score = $this->analyze->execute($post)['score'];
        $post->timestamps = false;
        $post->forceFill(['data' => array_merge($post->data ?? [], ['seo_score' => $score])])->saveQuietly();
    }
}
