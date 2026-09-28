@props(['post', 'variant' => 'default', 'heading' => 'h3'])
<article {{ $attributes->class(['media-card', 'media-card--'.$variant]) }}>
    <a class="media-card__image ratio-card" href="{{ $post->url }}" tabindex="-1" aria-hidden="true">
        <img class="img-cover" src="{{ $post->image_url }}" alt="" width="640" height="400" loading="lazy" decoding="async">
    </a>
    <div class="media-card__body">
        @if ($post->category)
            <a class="tag tag--gold align-self-start" href="{{ route('articles.category', $post->category->slug) }}">{{ $post->category_label }}</a>
        @endif
        <{{ $heading }} @class(['media-card__title', 'h3' => $variant !== 'featured', 'h2' => $variant === 'featured'])><a href="{{ $post->url }}">{{ $post->title }}</a></{{ $heading }}>
        @if ($variant !== 'row' && $post->excerpt)
            <p>{{ \Illuminate\Support\Str::limit($post->excerpt, $variant === 'featured' ? 220 : 130) }}</p>
        @endif
        <p class="media-card__meta"><x-icon name="calendar" class="icon-sm" /> {{ $post->published_label }} <span aria-hidden="true">·</span> {{ $post->read_time_label }}</p>
    </div>
</article>
