@extends('layouts.master')

@section('body_class', 'is-article')
@section('page_title', $caseStudy->title)

@section('content')
@if(!empty($isPreview) && $isPreview)
<div class="container"><div class="alert alert-warning mt-3" role="status">Bản xem trước — case study chưa đăng</div></div>
@endif
<article class="article-page">
    <div class="container">
        <div class="article-shell">
            <h1 class="article-hero__title">{{ $caseStudy->title }}</h1>
            <div class="news-content">{!! $caseStudy->content !!}</div>
        </div>
    </div>
</article>
@endsection
