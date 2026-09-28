@if ($paginator->hasPages())
    <nav aria-label="Phân trang">
        <ul class="pagination justify-content-center flex-wrap gap-1">
            <li @class(['page-item', 'disabled' => $paginator->onFirstPage()])>
                <a class="page-link" href="{{ $paginator->previousPageUrl() ?? '#' }}" rel="prev" aria-label="Trang trước"><x-icon name="chevron-left" class="icon-sm" /></a>
            </li>
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li @class(['page-item', 'active' => $page === $paginator->currentPage()])>
                            <a class="page-link" href="{{ $url }}" @if ($page === $paginator->currentPage()) aria-current="page" @endif>{{ $page }}</a>
                        </li>
                    @endforeach
                @endif
            @endforeach
            <li @class(['page-item', 'disabled' => ! $paginator->hasMorePages()])>
                <a class="page-link" href="{{ $paginator->nextPageUrl() ?? '#' }}" rel="next" aria-label="Trang sau"><x-icon name="chevron-right" class="icon-sm" /></a>
            </li>
        </ul>
    </nav>
@endif
