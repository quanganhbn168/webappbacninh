@props(['name', 'class' => ''])
<svg class="ph-icon {{ $class }}" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
    @switch($name)
        @case('leaf')
            <path d="M28 3C16 2 4 6 4 17c0 7 7 10 13 6C24 19 26 11 28 3Z" fill="#7fc83d" stroke="#64ae31"/>
            <path d="M5 23C11 14 17 12 25 6" stroke="#f0ffcf" stroke-width="1.3"/><path d="m2 29 9-14" stroke="#3b993d"/>
            <path d="M17 26c6 2 11-1 13-6-7-1-11 0-13 6Z" fill="#42a637" stroke="none"/>
            @break
        @case('air')
            <path d="M3 12h17c7 0 6-9 1-8-3 0-4 3-2 4M3 17h23c5 0 5 7 1 7-2 0-3-1-3-2M4 22h10c7 0 5 8 1 7-2 0-3-1-3-3"/>
            @break
        @case('quiet')
            <path d="m14 5-7 7H3v8h4l7 7V5Z" fill="currentColor" fill-opacity=".16"/><path d="m3 3 26 26M20 9c2 2 3 5 2 8M24 5c4 4 6 10 4 15"/>
            @break
        @case('bolt')
            <path d="M18 2 5 18h10l-2 12 14-18H16l2-10Z" fill="currentColor" stroke="none"/>
            @break
        @case('home')
            <path d="m3 15 13-11 13 11M7 13v15h7v-9h5v9h6V13" fill="currentColor" fill-opacity=".14"/><path d="M23 5v4"/>
            @break
        @case('shield')
            <path d="m16 3 11 4v9c0 6-6 10-11 13C11 26 5 22 5 16V7l11-4Z" fill="currentColor" fill-opacity=".16"/><path d="m11 15 4 4 7-8M16 4v23"/>
            @break
        @case('family')
            <circle cx="16" cy="8" r="4" fill="currentColor" fill-opacity=".2"/><circle cx="6" cy="11" r="3"/><circle cx="26" cy="11" r="3"/><path d="M10 28v-9c0-7 12-7 12 0v9M2 26v-7c0-3 4-5 7-2M30 26v-7c0-3-4-5-7-2M14 21v8M18 21v8"/>
            @break
        @case('heart')
            <path d="M16 28 5 17C-5 6 10-2 16 8 22-2 37 6 27 17L16 28Z" fill="currentColor" fill-opacity=".1"/><path d="M7 16h6l3-6 2 11 3-5h5"/>
            @break
        @case('diamond')
            <path d="m3 11 6-8h14l6 8-13 19L3 11Z" fill="currentColor" fill-opacity=".2"/><path d="M3 11h26M10 4l-1 7 7 19 7-19-1-7M9 11l7-8 7 8"/>
            @break
        @case('touch')
            <rect x="5" y="3" width="22" height="26" rx="3"/><rect x="8" y="6" width="16" height="20" rx="1" fill="currentColor" fill-opacity=".15"/><path d="M15 20v-8a2 2 0 0 1 4 0v5l3 1v5M15 18l-3-1v4l4 4"/>
            @break
        @case('design')
            <path d="m7 24 5-9 8-11 4 3-7 12-7 8-5 1 2-4ZM12 15l5 4M21 5l3-3M7 14l3-7M5 19l-2 5"/>
            @break
        @case('rotate')
            <path d="M27 12A12 12 0 0 0 7 7L3 11M3 4v7h7M5 21a12 12 0 0 0 21 4l3-4M29 28v-7h-7"/>
            @break
        @case('truck')
            <path d="M3 7h16v16H3V7ZM19 12h6l4 6v5H19M20 13v6h8M1 12h7M1 16h5"/><circle cx="9" cy="24" r="3" fill="white"/><circle cx="24" cy="24" r="3" fill="white"/>
            @break
        @case('cart')
            <path d="M2 4h4l4 17h16l4-13H7M11 16h16"/><circle cx="12" cy="27" r="2"/><circle cx="25" cy="27" r="2"/>
            @break
        @case('phone')
            <path d="m9 3 5 7-4 4c2 4 4 6 8 8l4-4 7 5c-1 7-7 7-12 4C8 23 2 15 3 8c0-3 3-5 6-5Z" fill="currentColor" fill-opacity=".12"/><path d="M21 4a8 8 0 0 1 7 7M21 9c2 0 3 1 3 3"/>
            @break
        @case('mail')
            <rect x="3" y="6" width="26" height="20" rx="3"/><path d="m4 8 12 10L28 8"/>
            @break
        @case('pin')
            <path d="M26 13c0 8-10 16-10 16S6 21 6 13a10 10 0 0 1 20 0Z" fill="currentColor" fill-opacity=".14"/><circle cx="16" cy="13" r="3"/>
            @break
        @case('gift')
            <path d="M5 14h22v15H5V14ZM3 9h26v6H3V9ZM16 9v20"/><path d="M16 9C4 10 6-2 12 3l4 6Zm0 0C28 10 26-2 20 3l-4 6Z"/>
            @break
        @case('arrow')
            <path d="M5 16h22m-7-7 7 7-7 7"/>
            @break
        @case('menu')
            <path d="M5 8h22M5 16h22M5 24h22"/>
            @break
        @case('close')
            <path d="m8 8 16 16M24 8 8 24"/>
            @break
    @endswitch
</svg>
