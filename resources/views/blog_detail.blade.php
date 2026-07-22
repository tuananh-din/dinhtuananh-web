@extends('layouts.master')
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
