@extends('layouts.master')
@section('page_title', 'Khóa học | ' . data_get($infor, 'name', 'Personal Brand'))
@section('og_title', 'Khóa học | ' . data_get($infor, 'name', 'Personal Brand'))
@section('content')
<style>
    .course-list-shell {
        padding: 110px 0;
    }

    .course-card {
        background: #121212;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 22px;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .course-thumb {
        position: relative;
    }

    .course-thumb img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }

    .course-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        background: #ff7a00;
        color: #fff;
        font-size: 12px;
        padding: 6px 10px;
        border-radius: 999px;
        z-index: 2;
    }

    .course-content {
        padding: 22px;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .course-content h3 {
        margin-bottom: 10px;
    }

    .course-content .desc {
        flex: 1;
        margin-bottom: 14px;
    }

    .course-price {
        font-weight: 700;
        margin-bottom: 14px;
    }

    .course-price .old {
        color: #999;
        text-decoration: line-through;
        margin-right: 8px;
        font-weight: 400;
    }
</style>

<section class="course-list-shell fix">
    <div class="container">
        <div class="section-title">
            <h6>Kh&#243;a h&#7885;c</h6>
            <h2>Danh s&#225;ch kh&#243;a h&#7885;c &#273;ang m&#7903; &#273;&#259;ng k&#253;</h2>
        </div>

        <div class="row g-4 mt-3">
            @forelse ($courses as $key => $row)
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".{{ ($key + 1) * 2 }}s">
                <article class="course-card">
                    <div class="course-thumb">
                        @if($row->is_featured)
                        <span class="course-badge">N&#7893;i b&#7853;t</span>
                        @endif
                        <a href="{{ route('course.detail', $row->slug) }}">
                            <img src="{{ $row->thumbnail ?: 'app/assets/images/others/thumb-16.jpg' }}" alt="{{ $row->title }}">
                        </a>
                    </div>

                    <div class="course-content">
                        <h3><a href="{{ route('course.detail', $row->slug) }}">{{ $row->title }}</a></h3>
                        <p class="desc">{{ $row->short_description ?: 'Xem chi ti&#7871;t &#273;&#7875; bi&#7871;t l&#7897; tr&#236;nh h&#7885;c ph&#249; h&#7907;p v&#7899;i m&#7909;c ti&#234;u c&#7911;a b&#7841;n.' }}</p>

                        <p class="course-price">
                            @if(!is_null($row->sale_price))
                                @if(!is_null($row->price))
                                    <span class="old">{{ number_format($row->price, 0, ',', '.') }}&#273;</span>
                                @endif
                                <span>{{ number_format($row->sale_price, 0, ',', '.') }}&#273;</span>
                            @elseif(!is_null($row->price))
                                <span>{{ number_format($row->price, 0, ',', '.') }}&#273;</span>
                            @else
                                <span>Li&#234;n h&#7879; t&#432; v&#7845;n h&#7885;c ph&#237;</span>
                            @endif
                        </p>

                        <a href="{{ route('course.detail', $row->slug) }}" class="theme-btn">
                            Xem chi ti&#7871;t
                            <i class="fa-solid fa-arrow-up-right"></i>
                        </a>
                    </div>
                </article>
            </div>
            @empty
            <div class="col-lg-12">
                <div class="course-card">
                    <div class="course-content">
                        <h3>Ch&#432;a c&#243; kh&#243;a h&#7885;c hi&#7875;n th&#7883;</h3>
                        <p class="desc">B&#7841;n c&#243; th&#7875; th&#234;m kh&#243;a h&#7885;c m&#7899;i trong khu v&#7921;c admin, sau &#273;&#243; b&#7853;t tr&#7841;ng th&#225;i hi&#7875;n th&#7883; &#273;&#7875; xu&#7845;t hi&#7879;n t&#7841;i &#273;&#226;y.</p>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <div class="page-nav-wrap text-center mt-4">
            {!! $courses->links('vendor.paginate') !!}
        </div>
    </div>
</section>
@endsection
