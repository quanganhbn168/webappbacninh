{{-- Links to the industry pages (/mau-website/{slug}). --}}
@props(['industries'])
<ul {{ $attributes->class('industry-links') }}>
    @foreach ($industries as $industry)
        <li><a href="{{ $industry->url }}">Mẫu website {{ mb_strtolower($industry->name) }} <x-icon name="arrow-right" /></a></li>
    @endforeach
</ul>
