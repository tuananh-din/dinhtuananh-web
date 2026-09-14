@if ($paginator->hasPages())
<nav aria-label="Phân trang khóa học">
<ul>
    @if ($paginator->onFirstPage())
    <li class="disabled"><span class="page-numbers" aria-disabled="true"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i><span class="visually-hidden">Trang trước</span></span></li>
    @else
    <li><a class="page-numbers" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Trang trước"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i></a></li>
    @endif

   @if($paginator->currentPage() > 2)
    <li class="hidden-xs"><a class="page-numbers" href="{{ $paginator->url(1) }}">1</a></li>
    @endif
    @if($paginator->currentPage() > 3)
        <li><span class="page-numbers" aria-hidden="true">…</span></li>
    @endif
    @foreach(range(1, $paginator->lastPage()) as $i)
        @if($i >= $paginator->currentPage() - 1 && $i <= $paginator->currentPage() + 1)
            @if ($i == $paginator->currentPage())
                <li class="active"><span class="page-numbers" aria-current="page">{{ $i }}<span class="visually-hidden">, trang hiện tại</span></span></li>
            @else
                <li><a class="page-numbers" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
            @endif
        @endif
    @endforeach
    @if($paginator->currentPage() < $paginator->lastPage() - 2)
        <li><span class="page-numbers" aria-hidden="true">…</span></li>
    @endif
    @if($paginator->currentPage() < $paginator->lastPage() - 1)
        <li><a class="page-numbers" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a></li>
    @endif


    @if ($paginator->hasMorePages())
    <li><a class="page-numbers" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Trang sau"><i class="fa-solid fa-chevron-right" aria-hidden="true"></i></a></li>
    @else
    <li class="disabled"><span class="page-numbers" aria-disabled="true"><i class="fa-solid fa-chevron-right" aria-hidden="true"></i><span class="visually-hidden">Trang sau</span></span></li>
    @endif
</ul>
</nav>
@endif
