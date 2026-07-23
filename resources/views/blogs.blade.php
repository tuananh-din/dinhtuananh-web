@extends('layouts.master')
@section('page_title', 'Blog | ' . data_get($infor, 'name', 'Personal Brand'))
@section('og_title', 'Blog | ' . data_get($infor, 'name', 'Personal Brand'))
@section('content')
<section class="news-grid-section1 fix">
    <div class="container">
        <h1>Blog</h1>
        <div class="row bb-bottom">
            @foreach ($blogs as $key => $row)
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".{{ ($key + 1) * 2 }}s">
                <div class="news-box-items-2">
                    <div class="thumb">
                        <a href="{{ route('blog', $row->slug) }}">
                            <img src="{{ $row->image_url }}" alt="{{ $row->title }}">
                        </a>
                    </div>
                    <div class="content">
                        <span class="post-date">{{ optional($row->created_at)->format('d/m/Y') }}</span>
                        <h3><a href="{{ route('blog', $row->slug) }}">{{ $row->title }}</a></h3>
                        @if($row->description)
                        <p>{{ $row->description }}</p>
                        @endif
                        <a href="{{ route('blog', $row->slug) }}" class="theme-btn">&#272;&#7885;c b&#224;i vi&#7871;t <i class="fa-solid fa-arrow-up-right"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="page-nav-wrap text-center">
            {!! $blogs->links('vendor.paginate') !!}
        </div>
    </div>
</section>
@endsection
