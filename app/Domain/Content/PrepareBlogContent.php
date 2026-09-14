<?php

namespace App\Domain\Content;

class PrepareBlogContent
{
    public function execute(?string $content): string
    {
        $sections = json_decode($content ?? '', true);
        if (! is_array($sections) || ! array_is_list($sections)) {
            return $content ?? '';
        }

        return collect($sections)->map(function (array $section): string {
            $html = filled($section['title'] ?? null) ? '<h2>'.e($section['title']).'</h2>' : '';
            foreach ($section['paragraphs'] ?? [] as $paragraph) {
                $html .= '<p>'.e($paragraph).'</p>';
            }
            if ($section['bullets'] ?? []) {
                $html .= '<ul>'.collect($section['bullets'])->map(fn ($item) => '<li>'.e($item).'</li>')->implode('').'</ul>';
            }

            return $html;
        })->implode('');
    }
}
