@props(['items' => []])
@if (count($items) > 1)
    <nav {{ $attributes->merge(['class' => 'site-breadcrumb']) }} aria-label="Breadcrumb">
        <ol class="breadcrumb">
            @foreach ($items as $item)
                @if ($loop->last)
                    <li class="breadcrumb-item active" aria-current="page">{{ $item['name'] }}</li>
                @else
                    <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['name'] }}</a></li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
