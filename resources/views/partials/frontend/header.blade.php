@php($menu = app(\App\Domain\Navigation\Actions\BuildMenuTree::class)->execute(\App\Models\Menu::HEADER))
<header class="site-header">
    <nav class="navbar navbar-expand-lg h-100 p-0" aria-label="Điều hướng chính">
        <div class="container header-inner">
            @include('partials.frontend.brand')

            <div class="offcanvas offcanvas-end main-nav-panel" tabindex="-1" id="main-nav" aria-labelledby="main-nav-title">
                <div class="offcanvas-header">
                    <span class="offcanvas-title fw-bold" id="main-nav-title">Menu</span>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#main-nav" aria-label="Đóng menu"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav main-nav">
                        @foreach ($menu as $index => $item)
                            @php($active = \App\Domain\Navigation\Actions\BuildMenuTree::isActive($item))
                            @if ($item['children'] !== [])
                                <li class="nav-item dropdown nav-dropdown">
                                    <a @class(['nav-link', 'active is-active' => $active]) href="{{ $item['url'] }}" @if ($active) aria-current="page" @endif @if ($item['new_tab']) target="_blank" rel="noopener" @endif>{{ $item['title'] }}</a>
                                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" aria-label="Mở danh sách {{ mb_strtolower($item['title']) }}"></button>
                                    <ul class="dropdown-menu dropdown-panel">
                                        @foreach ($item['children'] as $child)
                                            <li>
                                                <a class="dropdown-item" href="{{ $child['url'] }}" @if ($child['new_tab']) target="_blank" rel="noopener" @endif>
                                                    @if ($child['icon'])<i class="{{ $child['icon'] }} dropdown-item__icon" aria-hidden="true"></i>@endif
                                                    <span>{{ $child['title'] }}</span>
                                                    <i class="fa-solid fa-chevron-right dropdown-item__arrow" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a @class(['nav-link', 'active is-active' => $active]) href="{{ $item['url'] }}" @if ($active) aria-current="page" @endif @if ($item['new_tab']) target="_blank" rel="noopener" @endif>{{ $item['title'] }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="header-actions">
                <a class="btn btn--outline" href="{{ route('products') }}">Xem Demo</a>
                <button class="btn btn--primary" data-consult="Tư vấn dự án" type="button">Nhận tư vấn</button>
                <button class="btn btn--icon mobile-toggle navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#main-nav" aria-controls="main-nav" aria-label="Mở menu">
                    <i class="fa-solid fa-bars" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </nav>
</header>
