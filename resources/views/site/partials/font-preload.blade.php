{{--
    Fetches the two Roboto files Vietnamese text needs before the first paint. Without this the
    text is drawn in a fallback font and re-wraps when Roboto arrives, moving the page.
--}}
@php
    $manifest = public_path('build/manifest.json');
    $fonts = is_file($manifest)
        ? collect(json_decode((string) file_get_contents($manifest), true))
            ->filter(fn (array $entry, string $source): bool => (bool) preg_match('#/roboto-(latin|vietnamese)-wght-normal\.woff2$#', $source))
            ->pluck('file')
        : collect();
@endphp
@foreach ($fonts as $font)
    <link rel="preload" as="font" type="font/woff2" href="{{ asset('build/'.$font) }}" crossorigin>
@endforeach
