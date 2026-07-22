@extends('layouts.master')
@php
    // A-4: chuẩn hoá SEO/OG cho từng bài. Ưu tiên description ngắn, fallback từ content (155 ký tự).
    $blogDescription = \Illuminate\Support\Str::limit(
        strip_tags($blog->description ?: ($blog->content ?? '')),
        155
    );
    $blogBrand = data_get($infor, 'name', 'Personal Brand');
@endphp
@section('page_title', $blog->title . ' | ' . $blogBrand)
@section('meta_description', $blogDescription)
@section('og_title', $blog->title)
@section('og_description', $blogDescription)
@if(!empty($blog->image))
    @section('og_image', \Illuminate\Support\Str::startsWith($blog->image, ['http://','https://','//']) ? $blog->image : asset(ltrim($blog->image, '/')))
@endif
@section('content')
<section class="news-grid-section1 fix">
    <div class="container">
        <h1>{{ $blog->title }}</h1>
        @if($blog->description)
        <p class="mb-4">{{ $blog->description }}</p>
        @endif
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="news-details-area">
                    <div class="single-news-post">
                        <div class="news-content">
                            {!! $blog->content !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
