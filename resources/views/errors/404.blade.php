@extends('layouts.master')

@php
    $recentBlogs = collect();
    try {
        $recentBlogs = \App\Models\Blog::where('is_published', 1)->latest()->limit(3)->get();
    } catch (\Throwable $exception) {
        $recentBlogs = collect();
    }
@endphp

@section('page_title', 'Không tìm thấy trang | ' . data_get($infor, 'name', 'Personal Brand'))
@push('head')
<meta name="robots" content="noindex,nofollow">
@endpush

@section('content')
    <section class="error-page" aria-labelledby="error-page-title">
        <div class="error-page__flare" aria-hidden="true"></div>
        <div class="container">
            <div class="error-page__inner">
                <span class="error-page__code">404</span>
                <h1 id="error-page-title">Không tìm thấy trang</h1>
                <p>Trang bạn tìm không tồn tại hoặc đã được di chuyển.</p>
                <form class="error-page__search" action="{{ route('blogs') }}" method="GET" role="search">
                    <label for="error-page-search">Tìm nội dung khác trong Blog</label>
                    <div class="error-page__search-controls">
                        <input id="error-page-search" type="search" name="search" maxlength="120" autocomplete="off" placeholder="Ví dụ: Facebook Ads, dữ liệu…">
                        <button type="submit" class="theme-btn">Tìm trong Blog <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></button>
                    </div>
                    <p class="error-page__search-hint">Hoặc chọn một trong các lối tắt bên dưới.</p>
                </form>
                <div class="error-page__actions">
                    <a href="{{ route('index') }}" class="theme-btn">Về Trang chủ</a>
                    <a href="{{ route('contact') }}" class="theme-btn border-btn">Liên hệ tư vấn</a>
                    <a href="{{ route('courses') }}" class="theme-btn">Xem Khóa học</a>
                </div>
                @if($recentBlogs->isNotEmpty())
                <div class="error-page__suggestions">
                    <h2>Bài viết mới</h2>
                    <div class="row g-3 justify-content-center">
                        @foreach($recentBlogs as $blog)
                        <div class="col-12 col-md-4">
                            <a class="error-page__suggestion-card" href="{{ route('blog', $blog->public_slug) }}">{{ $blog->title }}</a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
@endsection
