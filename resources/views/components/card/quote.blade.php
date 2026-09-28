@props(['testimonial'])
<figure {{ $attributes->class(['quote-card']) }}>
    <x-icon name="quote" class="quote-card__mark" />
    <blockquote>{{ $testimonial->quote }}</blockquote>
    <figcaption>
        @if ($testimonial->avatar)
            <img src="{{ $testimonial->avatar->url }}" alt="" width="48" height="48" loading="lazy">
        @endif
        <span><strong>{{ $testimonial->name }}</strong>{{ $testimonial->role }}</span>
    </figcaption>
    <div class="quote-card__stars" role="img" aria-label="{{ $testimonial->rating }} trên 5 sao">
        @for ($i = 0; $i < $testimonial->rating; $i++)<x-icon name="star" />@endfor
    </div>
</figure>
