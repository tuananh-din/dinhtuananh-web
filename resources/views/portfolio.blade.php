@extends('layouts.master')
@section('page_title', 'Case Study | ' . data_get($infor, 'name', 'Personal Brand'))
@section('og_title', 'Case Study | ' . data_get($infor, 'name', 'Personal Brand'))
@section('content')
<section class="about-section-inner">
    <div class="container">
        <div class="about-inner-wrapper">
            <div class="about-head wow fadeInUp" data-wow-delay=".3s">
                <h1>Case Study</h1>
            </div>
            <h3 class="about-sub text_invert-2">
                N&#417;i tr&#236;nh b&#224;y nh&#7919;ng k&#7871;t qu&#7843;, n&#259;ng l&#7921;c v&#224; h&#432;&#7899;ng gi&#7843;i quy&#7871;t v&#7845;n &#273;&#7873; th&#7921;c t&#7871; thay v&#236; ch&#7881; l&#224; m&#7897;t gallery h&#236;nh &#7843;nh.
            </h3>
            <div class="about-image fix">
                <img data-speed=".7" src="{{ $image->image ?? $about->avatar ?? 'app/assets/images/others/thumb-16.jpg' }}" alt="case-study">
            </div>
        </div>
    </div>
</section>

<section class="approach-section fix section-padding pt-0">
    <div class="container">
        <div class="approach-wrapper">
            @foreach ($jobs as $row)
            <div class="approach-wrap-items">
                <h3 class="text_invert-2">{{ $row->title }}</h3>
                <p>{{ $row->description }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="approach-section fix section-padding pt-0">
    <div class="container">
        <div class="approach-wrapper">
            <h3 class="text_invert-2">Khung tr&#236;nh b&#224;y case study hi&#7879;n t&#7841;i</h3>
            <p>Version 1 d&#249;ng l&#7841;i d&#7919; li&#7879;u c&#361; &#273;&#7875; chuy&#7875;n portfolio th&#224;nh khu v&#7921;c ch&#7889;t uy t&#237;n. M&#7895;i h&#236;nh &#7843;nh c&#243; th&#7875; &#273;&#432;&#7907;c xem nh&#432; m&#7897;t d&#7921; &#225;n, m&#7897;t workshop, m&#7897;t k&#7871;t qu&#7843; ho&#7863;c m&#7897;t minh ch&#7913;ng social proof.</p>
            {!! $about->content !!}
        </div>
    </div>
</section>

<section class="favorite-tools-section fix section-padding pt-0">
    <div class="container">
        <div class="favorite-tools-wrapper">
            <h3><span>Core</span> Expertise</h3>
            <div class="vec-shape">
                <img src="{{ asset('site/assets/img/vec.png') }}" alt="img">
            </div>
            <div class="favorite-tools-items">
                @foreach ($skills as $row)
                <div class="skill-counter">
                    <div class="content">
                        <h2><span class="count">{{ $row->number }}</span>%</h2>
                        <p>{{ $row->name }}</p>
                    </div>
                </div>
                <div class="line-shape"></div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="project-section-3 fix section-padding pt-0">
    <div class="container">
        <div class="section-title pb-4">
            <h6>K&#7871;t qu&#7843; th&#7921;c t&#7871;</h6>
            <h2>H&#236;nh &#7843;nh, d&#7921; &#225;n v&#224; minh ch&#7913;ng &#273;&#7875; kh&#225;ch truy c&#7853;p hi&#7875;u r&#7857;ng b&#7841;n &#273;&#227; l&#224;m th&#7853;t</h2>
        </div>
    </div>
    <div class="swiper project-inner-slider">
        <div class="swiper-wrapper">
            @foreach ($lifes as $row)
            <div class="swiper-slide">
                <div class="project-inner-image">
                    <img src="{{ $row->image ?: 'app/assets/images/others/thumb-16.jpg' }}" alt="project">
                    <div class="content">
                        <h3><a>{{ $row->title ?: 'Case study' }}</a></h3>
                        <p>{{ $row->description ?: 'B&#7841;n c&#243; th&#7875; ti&#7871;p t&#7909;c qu&#7843;n l&#253; ph&#7847;n n&#224;y b&#7857;ng module Image c&#361; trong admin.' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="cta-section section-padding fix pt-0">
    <div class="container">
        <div class="cta-text-items text-center">
            <h2 class="text_invert-2">Mu&#7889;n xem c&#225;ch t&#244;i bi&#7871;n ki&#7871;n th&#7913;c th&#224;nh k&#7871;t qu&#7843; th&#7921;c t&#7871;?</h2>
            <p>H&#227;y b&#7855;t &#273;&#7847;u t&#7915; blog &#273;&#7875; hi&#7875;u c&#225;ch t&#244;i tr&#236;nh b&#224;y ki&#7871;n th&#7913;c, sau &#273;&#243; li&#234;n h&#7879; &#273;&#7875; nh&#7853;n t&#432; v&#7845;n kh&#243;a h&#7885;c ho&#7863;c chi&#7871;n l&#432;&#7907;c ph&#249; h&#7907;p.</p>
            <div class="cta-btn">
                <a href="{{ route('blogs') }}" class="theme-btn">Xem blog <i class="fa-solid fa-arrow-up-right"></i></a>
                <a href="tel:{{ $about->tel }}" class="theme-btn border-btn">Nh&#7853;n t&#432; v&#7845;n <i class="fa-solid fa-arrow-up-right"></i></a>
            </div>
        </div>
    </div>
</section>
@endsection
