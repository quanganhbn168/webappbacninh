@props(['items' => []])
<div {{ $attributes->merge(['class' => 'trust-row']) }}>
    <div class="container">
        <ul class="trust-row__list">
            @foreach ($items as $item)
                <li class="trust-row__item">
                    <x-icon :name="$item['icon']" />
                    <div>
                        <strong>{{ $item['title'] }}</strong>
                        @if (! empty($item['text']))
                            <span>{{ $item['text'] }}</span>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
