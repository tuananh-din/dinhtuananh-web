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

@section('content')
    <section class="error-page">
        <div class="container">
            <div class="error-page__inner">
                <span class="error-page__code">404</span>
                <h1>Không tìm thấy trang</h1>
                <p>Trang bạn tìm không tồn tại hoặc đã được di chuyển.</p>
                <div class="error-page__actions">
                    <a href="{{ route('index') }}" class="theme-btn">Về Trang chủ</a>
                    <a href="{{ route('blogs') }}" class="theme-btn">Xem Blog</a>
                    <a href="{{ route('courses') }}" class="theme-btn">Xem Khóa học</a>
                </div>
                @if($recentBlogs->isNotEmpty())
                <div class="error-page__suggestions mt-5">
                    <h2>Bài viết mới</h2>
                    <div class="row g-3">
                        @foreach($recentBlogs as $blog)
                        <div class="col-md-4"><a href="{{ route('blog', $blog->slug) }}">{{ $blog->title }}</a></div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
@endsection
