<?php

namespace App\Services;

use App\Models\Template;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TemplateService
{
    public function create(array $data, $imageFile = null)
    {
        $data['slug'] = Str::slug($data['name']);
        
        if ($imageFile) {
            $filename = time() . '_' . $imageFile->getClientOriginalName();
            $imageFile->move(public_path('uploads/templates'), $filename);
            $data['image'] = 'uploads/templates/' . $filename;
        }

        return Template::create($data);
    }

    public function update(Template $template, array $data, $imageFile = null)
    {
        $data['slug'] = Str::slug($data['name']);

        if ($imageFile) {
            // Delete old image if exists
            if ($template->image && file_exists(public_path($template->image))) {
                unlink(public_path($template->image));
            }

            $filename = time() . '_' . $imageFile->getClientOriginalName();
            $imageFile->move(public_path('uploads/templates'), $filename);
            $data['image'] = 'uploads/templates/' . $filename;
        }

        $template->update($data);
        return $template;
    }

    public function delete(Template $template)
    {
        if ($template->image && file_exists(public_path($template->image))) {
            unlink(public_path($template->image));
        }
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
}
