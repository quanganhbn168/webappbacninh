@props(['landing', 'position'])
{{-- A demo measures nothing, so trying it out never counts as a conversion. --}}
@unless ($landing->isDemo)
    {!! tracking_code($position) !!}
    {{-- The page's own campaign code; signed-in admins are left out, like in the site-wide code. --}}
    @if ($position === 'head' && ! auth('admin')->check())
        {!! $landing->head_code !!}
    @endif
@endunless
