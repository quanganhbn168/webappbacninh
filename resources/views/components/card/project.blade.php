@props(['project', 'heading' => 'h3'])
<article {{ $attributes->class(['media-card']) }}>
    <a class="media-card__image ratio-card" href="{{ $project->url }}" tabindex="-1" aria-hidden="true">
        <img class="img-cover" src="{{ $project->image_url }}" alt="" width="640" height="400" loading="lazy" decoding="async">
        <span class="media-card__badge">{{ $project->category_label }}</span>
    </a>
    <div class="media-card__body">
        <{{ $heading }} class="h3 media-card__title"><a href="{{ $project->url }}">{{ $project->title }}</a></{{ $heading }}>
        @if ($project->excerpt)
            <p>{{ \Illuminate\Support\Str::limit($project->excerpt, 150) }}</p>
        @endif
        <a class="link-arrow mt-auto" href="{{ $project->url }}">Xem chi tiết <x-icon name="arrow-right" /></a>
    </div>
</article>
