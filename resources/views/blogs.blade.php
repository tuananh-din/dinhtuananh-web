@extends('layouts.master')
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
                            <img src="{{ $row->image ?: 'app/assets/images/others/thumb-16.jpg' }}" alt="{{ $row->title }}">
                        </a>
                    </div>
                    <div class="content">
                        <h3><a href="{{ route('blog', $row->slug) }}">{{ $row->title }}</a></h3>
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
