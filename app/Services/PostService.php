<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Str;

class PostService
{
    /**
     * Create a new post.
     */
    public function create(array $data): Post
    {
        $postData = $this->prepareData($data);
        $post = Post::create($postData);
        
        $this->syncTags($post, $data['tags'] ?? []);
        
        return $post;
    }

    /**
     * Update an existing post.
     */
    public function update(Post $post, array $data): Post
    {
        $postData = $this->prepareData($data, $post);
        $post->update($postData);
        
        $this->syncTags($post, $data['tags'] ?? []);
        
        return $post;
    }

    /**
     * Delete a post.
     */
    public function delete(Post $post): bool
    {
        return $post->delete();
    }

    /**
     * Prepare data for creating/updating.
     */
    protected function prepareData(array $data, ?Post $existingPost = null): array
    {
        $postData = collect($data)->only([
            'category_id', 'title', 'slug', 'summary', 'content',
            'meta_title', 'meta_description', 'meta_keywords',
            'featured_image', 'og_image'
        ])->toArray();
        
        // Handle slug
        if (!empty($postData['slug'])) {
            $postData['slug'] = Str::slug($postData['slug']);
        } elseif ($existingPost) {
            unset($postData['slug']); // Keep existing slug
        } else {
            unset($postData['slug']); // Let Spatie generate it
        }
        
        // Handle publish status
        $postData['is_published'] = isset($data['is_published']);
        
        if ($postData['is_published']) {
            if (!$existingPost || !$existingPost->is_published) {
                $postData['published_at'] = now();
            }
        } else {
            $postData['published_at'] = null;
        }
        
        return $postData;
    }

    /**
     * Sync tags - create new ones if they don't exist.
     */
    protected function syncTags(Post $post, array $tags): void
    {
        $tagIds = [];
        
        foreach ($tags as $tag) {
            if (is_numeric($tag)) {
                $tagIds[] = (int) $tag;
            } elseif (!empty($tag)) {
                // Create new tag if it doesn't exist
                $newTag = Tag::firstOrCreate(
                    ['name' => $tag],
                    ['slug' => Str::slug($tag)]
                );
                $tagIds[] = $newTag->id;
            }
        }
        
        $post->tags()->sync($tagIds);
    }
}
