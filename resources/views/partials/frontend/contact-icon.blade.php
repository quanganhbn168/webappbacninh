<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
@switch($icon)
    @case('phone') @case('whatsapp')
        <path d="m5 2 4 1 1 5-3 2a16 16 0 0 0 7 7l2-3 5 1 1 4-2 3C10 22 2 14 2 4Z"/>
        @break
    @case('top')
        <path d="m6 12 6-6 6 6M12 6v14"/>
        @break
    @case('telegram')
        <path d="m3 10 18-7-5 18-5-7-8-4Zm8 4L21 3"/>
        @break
    @case('messenger')
        <path d="M21 11a9 9 0 0 1-9 9H5l-3 2 1-6a9 9 0 1 1 18-5Z"/><path d="m6 13 4-4 4 4 4-4"/>
        @break
    @case('zalo')
        <path d="M21 11a9 9 0 0 1-9 9H5l-3 2 1-6a9 9 0 1 1 18-5Z"/><path d="M8 8h8l-8 7h8"/>
        @break
    @default
        <path d="M21 11a9 9 0 0 1-9 9H5l-3 2 1-6a9 9 0 1 1 18-5Z"/><path d="M7 10h10M7 14h6"/>
@endswitch
</svg>
