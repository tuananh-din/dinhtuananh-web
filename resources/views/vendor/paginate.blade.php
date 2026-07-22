@if ($paginator->hasPages())
<ul>
    @if ($paginator->onFirstPage())
    <li class="disabled"><a class="page-numbers"><i class="fa-solid fa-chevron-left"></i></a></li>
    @else
    <li><a class="page-numbers" href="{{ $paginator->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a></li>
    @endif

   @if($paginator->currentPage() > 2)
    <li class="hidden-xs"><a class="page-numbers" href="{{ $paginator->url(1) }}">1</a></li>
    @endif
    @if($paginator->currentPage() > 3)
        <li><a class="page-numbers">...</a></li>
    @endif
    @foreach(range(1, $paginator->lastPage()) as $i)
        @if($i >= $paginator->currentPage() - 1 && $i <= $paginator->currentPage() + 1)
            @if ($i == $paginator->currentPage())
                <li class="active"><a class="page-numbers">{{ $i }}</a></li>
            @else
                <li><a class="page-numbers" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
            @endif
        @endif
    @endforeach
    @if($paginator->currentPage() < $paginator->lastPage() - 2)
        <li><a class="page-numbers">...</a></li>
    @endif
    @if($paginator->currentPage() < $paginator->lastPage() - 1)
        <li><a class="page-numbers" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a></li>
    @endif


    @if ($paginator->hasMorePages())
    <li ><a class="page-numbers" href="{{ $paginator->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a></li>
    @else
    <li class="disabled"><a class="page-numbers"><i class="fa-solid fa-chevron-right"></i></a></li>
    @endif
</ul>
@endif
