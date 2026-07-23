@extends('layouts.master')
@section('page_title', 'Giới thiệu | ' . data_get($infor, 'name', 'Personal Brand'))
@section('og_title', 'Giới thiệu | ' . data_get($infor, 'name', 'Personal Brand'))
@section('content')
<section class="about-section-inner">
    <div class="container">
        <div class="about-inner-wrapper">
            <div class="about-head wow fadeInUp" data-wow-delay=".3s">
                <h1>V&#7873; t&#244;i</h1>
            </div>
            <h3 class="about-sub text_invert-2">
                X&#226;y d&#7921;ng th&#432;&#417;ng hi&#7879;u c&#225; nh&#226;n &#273;&#225;ng tin c&#7853;y &#273;&#7875; chia s&#7867; ki&#7871;n th&#7913;c ads, t&#432; v&#7845;n chi&#7871;n l&#432;&#7907;c v&#224; m&#7903; b&#225;n kh&#243;a h&#7885;c d&#7877; h&#417;n.
            </h3>
            <div class="about-image fix">
                <img data-speed=".7" src="{{ $image->image ?? $about->avatar ?? 'app/assets/images/others/thumb-16.jpg' }}" alt="{{ $about->name }}">
            </div>
        </div>
    </div>
</section>

<section class="about-counter-section fix section-padding pt-0">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-lg-7">
                <div class="about-grid-card">
                    <h2>{{ $about->name }}</h2>
                    <p>{{ $about->description }}</p>
                    {!! $about->about_me !!}
                </div>
            </div>
            <div class="col-lg-5" id="contact-brand">
                <div class="about-grid-card">
                    <h3>Th&#244;ng tin li&#234;n h&#7879;</h3>
                    <p>N&#7871;u b&#7841;n mu&#7889;n t&#432; v&#7845;n kh&#243;a h&#7885;c ho&#7863;c h&#7907;p t&#225;c tri&#7875;n khai ads, &#273;&#226;y l&#224; &#273;i&#7875;m li&#234;n h&#7879; nhanh nh&#7845;t.</p>
                    <ul class="cta-list">
                        <li>Phone / Zalo: <a href="tel:{{ $about->tel }}">{{ $about->tel }}</a></li>
                        <li>Email: <a href="mailto:{{ $about->email }}">{{ $about->email }}</a></li>
                        <li>&#272;&#7883;a ch&#7881;: {{ $about->address }}</li>
                    </ul>
                    <div class="cta-inline">
                        <a href="tel:{{ $about->tel }}" class="theme-btn">Nh&#7853;n t&#432; v&#7845;n <i class="fa-solid fa-arrow-up-right"></i></a>
                        <a href="{{ route('index') }}#courses" class="theme-btn border-btn">Xem &#273;&#7883;nh h&#432;&#7899;ng kh&#243;a h&#7885;c <i class="fa-solid fa-arrow-up-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding pt-0">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="about-grid-card">
                    <h3>B&#7841;n c&#243; th&#7875; gi&#250;p kh&#225;ch h&#224;ng &#273;i&#7873;u g&#236;?</h3>
                    @foreach ($jobs as $row)
                    <div class="mb-3">
                        <h5>{{ $row->title }}</h5>
                        <p>{{ $row->description }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-grid-card">
                    <h3>N&#259;ng l&#7921;c c&#7889;t l&#245;i</h3>
                    @foreach ($skills as $row)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $row->name }}</strong>
                            <span>{{ $row->number }}%</span>
                        </div>
                        <p>{{ $row->description }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
