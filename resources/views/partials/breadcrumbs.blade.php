@if(!empty($items))
<nav class="site-breadcrumbs" aria-label="Breadcrumb">
    <ol>
        @foreach($items as $item)
            <li>
                @if(!$loop->last && !empty($item['url']))
                    <a href="{{ $item['url'] }}">{{ $item['name'] }}</a>
                @else
                    <span aria-current="page">{{ $item['name'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif
