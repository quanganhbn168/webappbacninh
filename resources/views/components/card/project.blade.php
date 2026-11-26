@props(['project', 'heading' => 'h3'])
<article {{ $attributes->class(['media-card', 'project-card']) }}>
    <a class="media-card__image ratio-card" href="{{ $project->url }}" tabindex="-1" aria-hidden="true">
        <x-img :src="$project->cover" sizes="(min-width: 992px) 33vw, (min-width: 768px) 50vw, 100vw" class="w-100 h-100 object-fit-cover" alt="{{ $project->title }}" width="640" height="400" loading="lazy" decoding="async" />
        <span class="media-card__badge">{{ $project->category_label }}</span>
    </a>
    <div class="media-card__body">
        <{{ $heading }} class="h3 media-card__title"><a href="{{ $project->url }}">{{ $project->title }}</a></{{ $heading }}>
        @if ($project->excerpt)
            <p>{{ \Illuminate\Support\Str::limit($project->excerpt, 150) }}</p>
        @endif
        <div class="project-card__foot mt-auto">
            <a class="link-arrow" href="{{ $project->url }}">Xem chi tiết <x-icon name="arrow-right" /></a>
            @if (filled($project->cost))<strong class="project-card__cost">{{ $project->cost }}</strong>@endif
        </div>
    </div>
</article>
