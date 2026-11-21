<x-filament-widgets::widget>
    <x-filament::section :heading="$heading" :description="$source" compact>
        @if ($error)
            <p class="text-sm text-danger-600 dark:text-danger-400">{{ $error }}</p>
        @elseif ($rows === [])
            <p class="text-sm text-gray-500">Chưa có số liệu trong khoảng thời gian này.</p>
        @else
            <div class="-mx-2 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-500 dark:text-gray-400">
                            @foreach ($columns as $index => $column)
                                <th @class(['px-2 pb-2 font-medium', 'text-left' => $index === 0, 'text-right whitespace-nowrap' => $index > 0])>{{ $column }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $row)
                            <tr class="border-t border-gray-100 dark:border-white/5">
                                @foreach ($row['cells'] as $index => $cell)
                                    @if ($index === 0)
                                        <td class="relative max-w-0 w-full px-2 py-2">
                                            @isset($row['bar'])
                                                <span class="absolute inset-y-1 left-0 rounded bg-primary-500/10" style="width: {{ max(2, round($row['bar'] * 100)) }}%"></span>
                                            @endisset
                                            @if (! empty($row['href']))
                                                <a href="{{ $row['href'] }}" target="_blank" rel="noopener" class="relative block truncate text-gray-950 hover:text-primary-600 hover:underline dark:text-white" title="{{ $cell }}">{{ $cell }}</a>
                                            @else
                                                <span class="relative block truncate text-gray-950 dark:text-white" title="{{ $cell }}">{{ $cell }}</span>
                                            @endif
                                            @if (! empty($row['sub']))
                                                <span class="relative block truncate text-xs text-gray-500 dark:text-gray-400" title="{{ $row['sub'] }}">{{ $row['sub'] }}</span>
                                            @endif
                                        </td>
                                    @else
                                        <td class="whitespace-nowrap px-2 py-2 text-right tabular-nums text-gray-700 dark:text-gray-300">{{ $cell }}</td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
