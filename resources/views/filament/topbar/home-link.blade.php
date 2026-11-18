{{-- Next to ☰ in the topbar: the public site, in a new tab (APP_URL). --}}
<a href="{{ config('app.url') }}" target="_blank" rel="noopener"
    class="ms-2 flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-600 dark:text-gray-200 dark:hover:bg-white/5">
    Trang chủ
    <x-filament::icon icon="heroicon-m-arrow-top-right-on-square" class="h-4 w-4 text-gray-400" />
</a>
