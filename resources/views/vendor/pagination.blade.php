@if ($paginator->hasPages())
<nav aria-label="Phân trang">
    <ul class="pagination">
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link" aria-disabled="true">Trước</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Trước</a>
            </li>
        @endif
        @foreach ($elements as $element)

            @if (is_string($element))
                <li class="page-item">
                    <span class="page-link" aria-hidden="true">{{ $element }}</span>
                </li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <span class="page-link" aria-current="page">{{ $page }}<span class="visually-hidden">, trang hiện tại</span></span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Sau</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link" aria-disabled="true">Sau</span>
            </li>
        @endif
    </ul>
</nav>
@endif
