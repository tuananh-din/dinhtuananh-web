@extends('layouts.master')
@section('page_title', 'Khóa học | ' . data_get($infor, 'name', 'Personal Brand'))
@section('og_title', 'Khóa học | ' . data_get($infor, 'name', 'Personal Brand'))
@section('content')
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
                        <p class="desc">{{ $row->short_description ?: 'Xem chi tiết để biết lộ trình học phù hợp với mục tiêu của bạn.' }}</p>

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
                        <p class="desc">Kh&#243;a h&#7885;c m&#7899;i &#273;ang &#273;&#432;&#7907;c c&#7853;p nh&#7853;t. H&#227;y &#273;&#7875; l&#7841;i th&#244;ng tin &#273;&#7875; nh&#7853;n t&#432; v&#7845;n l&#7897; tr&#236;nh ph&#249; h&#7907;p.</p>
                        <a href="{{ route('contact') }}" class="theme-btn">Nh&#7853;n t&#432; v&#7845;n kh&#243;a h&#7885;c <i class="fa-solid fa-arrow-up-right"></i></a>
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
