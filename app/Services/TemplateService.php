<?php

namespace App\Services;

use App\Models\Template;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TemplateService
{
    public function create(array $data)
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        $template = Template::create($data);
        $this->importMedia($template, $data);

        // Process Tags
        if (isset($data['tags'])) {
            $this->syncTags($template, $data['tags']);
        }

        return $template;
    }

    public function update(Template $template, array $data)
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        $template->update($data);
        $this->importMedia($template, $data);

        // Process Tags
        if (isset($data['tags'])) {
            $this->syncTags($template, $data['tags']);
        }

        return $template;
    }

    public function delete(Template $template)
    {
        // Image is managed by LFM, so we might not want to auto-delete physical file 
        // as it might be used elsewhere.
        // User requested: "lưu là lưu cái gì (link hay upload)", implicating simple link storage.
        return $template->delete();
    }

    public function bulkDelete(array $ids)
    {
        $templates = Template::whereIn('id', $ids)->get();
        $count = 0;
        foreach ($templates as $template) {
            $this->delete($template);
            $count++;
        }
        return $count;
    }

    public function updateOrder(array $order)
    {
        foreach ($order as $index => $id) {
            Template::where('id', $id)->update(['order' => $index + 1]);
        }
    }

    protected function syncTags(Template $template, $tags)
    {
        if (is_string($tags)) {
           // Handle legacy comma separated string if any, though we use select now
           $tags = explode(',', $tags);
        }
        
        $tagIds = [];
        if (is_array($tags)) {
             foreach ($tags as $tag) {
                if (is_numeric($tag)) {
                    $tagIds[] = (int) $tag;
                } elseif (!empty($tag)) {
                    // Create new tag
                    $newTag = \App\Models\Tag::firstOrCreate(
                        ['name' => $tag],
                        ['slug' => Str::slug($tag)]
                    );
                    $tagIds[] = $newTag->id;
                }
            }
        }
       
        $template->tags()->sync($tagIds);
    }

    private function importMedia(Template $template, array $data): void
    {
        if (!empty($data['image'])) {
            $template->importMediaFromLegacyPath($data['image'], 'featured');
        }

        if (!empty($data['images'])) {
            $template->importMediaCollectionFromLegacyPaths($data['images'], 'gallery');
        }
    }
}
