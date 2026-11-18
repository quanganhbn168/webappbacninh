@php($menu = app(\App\Domain\Navigation\Actions\BuildMenuTree::class)->execute(\App\Models\Menu::HEADER))
<header class="site-header">
    <nav class="navbar navbar-expand-lg h-100 p-0" aria-label="Điều hướng chính">
        <div class="container site-header__inner">
            @include('site.partials.brand')

            <div class="offcanvas offcanvas-end site-nav" tabindex="-1" id="site-nav" aria-labelledby="site-nav-title">
                <div class="offcanvas-header">
                    <span class="offcanvas-title fw-bold" id="site-nav-title">Menu</span>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#site-nav" aria-label="Đóng menu"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav site-nav__list">
                        @foreach ($menu as $item)
                            @php($active = \App\Domain\Navigation\Actions\BuildMenuTree::isActive($item))
                            @if ($item['children'] !== [])
                                <li class="nav-item dropdown site-nav__dropdown">
                                    <a @class(['nav-link', 'active' => $active]) href="{{ $item['url'] }}" @if ($active) aria-current="page" @endif @if ($item['new_tab']) target="_blank" rel="noopener" @endif><span class="site-nav__text">{{ $item['title'] }}</span></a>
                                    <button class="dropdown-toggle site-nav__toggle" type="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" aria-label="Mở danh sách {{ mb_strtolower($item['title']) }}"><x-icon name="chevron-down" class="site-nav__caret" /></button>
                                    <ul class="dropdown-menu site-nav__panel">
                                        @foreach ($item['children'] as $child)
                                            <li>
                                                <a class="dropdown-item" href="{{ $child['url'] }}" @if ($child['new_tab']) target="_blank" rel="noopener" @endif>
                                                    <x-icon :name="$child['icon'] ?: 'chevron-right'" class="site-nav__icon" />
                                                    <span>{{ $child['title'] }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a @class(['nav-link', 'active' => $active]) href="{{ $item['url'] }}" @if ($active) aria-current="page" @endif @if ($item['new_tab']) target="_blank" rel="noopener" @endif><span class="site-nav__text">{{ $item['title'] }}</span></a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    <div class="site-nav__actions d-lg-none">
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#consult-modal">Nhận tư vấn</button>
                    </div>
                </div>
            </div>

            <div class="site-header__actions">
                <button class="btn btn-primary d-none d-sm-inline-flex" type="button" data-bs-toggle="modal" data-bs-target="#consult-modal">Nhận tư vấn</button>
                <button class="btn btn-icon d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#site-nav" aria-controls="site-nav" aria-label="Mở menu">
                    <x-icon name="menu" />
                </button>
            </div>
        </div>
    </nav>
</header>
