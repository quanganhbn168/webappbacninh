@props(['post', 'variant' => 'default', 'heading' => 'h3', 'dated' => true])
<article {{ $attributes->class(['media-card', 'media-card--'.$variant]) }}>
    <a class="media-card__image ratio-card" href="{{ $post->url }}" tabindex="-1" aria-hidden="true">
        <x-img :src="$post->cover" sizes="(min-width: 992px) 33vw, (min-width: 768px) 50vw, 100vw" class="w-100 h-100 object-fit-cover" :alt="$post->title" width="640" height="400" loading="lazy" decoding="async" />
    </a>
    <div class="media-card__body">
        @if ($post->category)
            <a class="tag tag--gold align-self-start" href="{{ $post->category->url }}">{{ $post->category_label }}</a>
        @endif
        <{{ $heading }} @class(['media-card__title', 'h3' => $variant !== 'featured', 'h2' => $variant === 'featured'])><a href="{{ $post->url }}">{{ $post->title }}</a></{{ $heading }}>
        @if ($variant !== 'row' && $post->excerpt)
            <p>{{ \Illuminate\Support\Str::limit($post->excerpt, $variant === 'featured' ? 220 : 130) }}</p>
        @endif
        <p class="media-card__meta">
            @if ($dated)
                <x-icon name="calendar" class="icon-sm" /> {{ $post->published_label }} <span aria-hidden="true">·</span>
            @endif
            {{ $post->read_time_label }}
        </p>
    </div>
</article>
