{{-- The panel of a mega menu item: sub-items in columns named by their "column title". --}}
<div class="dropdown-menu site-mega">
    <div class="container">
        <div class="site-mega__grid">
            @foreach (collect($item['children'])->groupBy('column') as $heading => $links)
                {{-- A column with descriptions lists its links one per row; one without lays them out side by side. --}}
                @php($described = $links->contains(fn (array $link): bool => filled($link['description'])))
                <div @class(['site-mega__column', 'site-mega__column--described' => $described])>
                    @if ($heading !== '')<p class="site-mega__heading">{{ $heading }}</p>@endif
                    <ul class="site-mega__list">
                        @foreach ($links as $link)
                            <li>
                                <a class="site-mega__link" href="{{ $link['url'] }}" @if ($link['new_tab']) target="_blank" rel="noopener" @endif>
                                    <span class="site-mega__icon"><x-icon :name="$link['icon'] ?: 'chevron-right'" /></span>
                                    <span>
                                        <span class="site-mega__title">{{ $link['title'] }}@if ($link['badge'])<em class="nav-badge">{{ $link['badge'] }}</em>@endif</span>
                                        @if ($link['description'])<small>{{ $link['description'] }}</small>@endif
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</div>
