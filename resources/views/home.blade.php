@extends('layouts.master')
@push('structured_data')
    @include('partials.jsonld-person', ['about' => $about])
@endpush
@section('content')
<section class="hero-section hero-1 hero-section1 fix">
    <div class="line-shape">
        <img src="{{ asset('site/assets/img/home-1/hero/line-shape.png') }}" alt="img">
    </div>

    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-xl-7">
                <div class="hero-content">
                    <span class="brand-eyebrow wow fadeInUp">Personal Brand - Multi-platform Ads</span>
                    <h1 class="brand-hero-title wow fadeInUp" data-wow-delay=".2s">
                        <span class="title-name">{{ $about->name }}</span>
                        <span class="title-sub">gi&#250;p doanh nghi&#7879;p v&#224; marketer</span>
                        <strong id="typing-text"></strong>
                    </h1>
                    <p class="wow fadeInUp" data-wow-delay=".3s">
                        {{ $about->description }}
                    </p>
                    <ul class="hero-bullets wow fadeInUp" data-wow-delay=".4s">
                        <li>&#272;&#224;o t&#7841;o v&#224; chia s&#7867; ki&#7871;n th&#7913;c Facebook Ads, TikTok Ads, Google Ads theo h&#432;&#7899;ng d&#7877; &#225;p d&#7909;ng ngay.</li>
                        <li>X&#226;y d&#7921;ng uy t&#237;n c&#225; nh&#226;n b&#7857;ng case study, b&#224;i vi&#7871;t chuy&#234;n m&#244;n v&#224; k&#7871;t qu&#7843; th&#7921;c t&#7871;.</li>
                        <li>T&#432; v&#7845;n h&#432;&#7899;ng &#273;i ph&#249; h&#7907;p cho ch&#7911; shop, doanh nghi&#7879;p nh&#7887; v&#224; ng&#432;&#7901;i m&#7899;i v&#224;o ngh&#7873;.</li>
                    </ul>
                    <div class="cta-inline wow fadeInUp" data-wow-delay=".5s">
                        <a href="#courses" class="theme-btn">Xem kh&#243;a h&#7885;c <i class="fa-solid fa-arrow-up-right"></i></a>
                        <a href="{{ route('portfolio') }}" class="theme-btn border-btn">Xem case study <i class="fa-solid fa-arrow-up-right"></i></a>
                    </div>
                    <div class="social-link wow fadeInUp" data-wow-delay=".6s">
                        @if($about->facebook)<a href="{{ $about->facebook }}">Facebook</a>@endif
                        @if($about->instagram)<a href="{{ $about->instagram }}">Instagram</a>@endif
                        @if($about->x)<a href="{{ $about->x }}">X</a>@endif
                        @if($about->linkedin)<a href="{{ $about->linkedin }}">LinkedIn</a>@endif
                    </div>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="brand-card wow fadeInUp" data-wow-delay=".3s">
                    <div class="hero-image image-wrapper">
                        <img class="animated-image" src="{{ $about->avatar ?: 'app/assets/images/others/thumb-16.jpg' }}" alt="{{ $about->name }}" width="100%">
                    </div>
                    <div class="brand-metrics">
                        <div class="metric">
                            <h3>{{ $jobs->count() }}+</h3>
                            <p>H&#432;&#7899;ng &#273;&#224;o t&#7841;o / gi&#7843;i ph&#225;p &#273;ang tri&#7875;n khai</p>
                        </div>
                        <div class="metric">
                            <h3>{{ $blogs->count() }}+</h3>
                            <p>N&#7897;i dung chia s&#7867; &#273;ang c&#243; s&#7861;n tr&#234;n website</p>
                        </div>
                        <div class="metric">
                            <h3>{{ $skills->count() }}+</h3>
                            <p>N&#259;ng l&#7921;c c&#7889;t l&#245;i &#273;ang &#273;&#432;&#7907;c th&#7875; hi&#7879;n</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-shell fix">
    <div class="container">
        <div class="section-title text-center">
            <h6>B&#7841;n gi&#250;p ai</h6>
            <h2>Nh&#243;m kh&#225;ch h&#224;ng ph&#249; h&#7907;p nh&#7845;t v&#7899;i th&#432;&#417;ng hi&#7879;u c&#7911;a b&#7841;n</h2>
        </div>
        <div class="row g-4 mt-4">
            <div class="col-lg-4">
                <div class="brand-card">
                    <h3>Ch&#7911; shop v&#224; SME</h3>
                    <p>C&#7847;n m&#7897;t l&#7897; tr&#236;nh qu&#7843;ng c&#225;o &#273;&#7875; ra &#273;&#417;n v&#224; ki&#7875;m so&#225;t chi ph&#237; d&#7877; d&#224;ng h&#417;n.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="brand-card">
                    <h3>Marketer m&#7899;i v&#224;o ngh&#7873;</h3>
                    <p>Mu&#7889;n h&#7885;c th&#7921;c chi&#7871;n, d&#7877; hi&#7875;u, c&#243; case study v&#224; c&#243; ng&#432;&#7901;i gi&#7843;i th&#237;ch r&#245; r&#224;ng.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="brand-card">
                    <h3>Doanh nghi&#7879;p c&#7847;n ng&#432;&#7901;i &#273;&#7891;ng h&#224;nh</h3>
                    <p>C&#7847;n t&#432; v&#7845;n chi&#7871;n l&#432;&#7907;c &#273;a k&#234;nh t&#7915; Facebook Ads, TikTok Ads &#273;&#7871;n Google Ads.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="choose-us-section fix section-shell pt-0">
    <div class="container">
        <div class="section-title-area">
            <div class="section-title">
                <h6>D&#7883;ch v&#7909; / gi&#7843;i ph&#225;p</h6>
                <h2>B&#7841;n c&#243; th&#7875; tri&#7875;n khai th&#432;&#417;ng hi&#7879;u v&#224; kh&#243;a h&#7885;c tr&#234;n m&#7897;t n&#7873;n t&#7843;ng &#273;&#225;ng tin c&#7853;y</h2>
            </div>
        </div>
        <div class="choose-us-wrapper">
            <div class="row g-4">
                <div class="col-lg-6">
                    <ul class="choose-us-box-list">
                        @foreach ($jobs as $key => $row)
                        <li class="wow fadeInUp" data-wow-delay=".{{ ($key + 1) * 2 }}s">
                            <div class="content">
                                <h2>{{ $row->title }}</h2>
                                <p>{{ $row->description }}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="brand-card wow fadeInUp" data-wow-delay=".3s">
                        <h3>Nội dung và uy tín — nền tảng để khách tin và đăng ký</h3>
                        <p>Trình bày rõ năng lực, blog kiến thức và case study thực tế, cùng lối đăng ký nhanh giúp bạn dễ ra quyết định.</p>
                        <div class="content mt-4">
                            {!! $about->content !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-shell pt-0" id="courses">
    <div class="container">
        <div class="section-title">
            <h6>Kh&#243;a h&#7885;c n&#7893;i b&#7853;t</h6>
            <h2>Khóa học đang mở đăng ký</h2>
        </div>
        <div class="row g-4 mt-4">
            @if($featuredCourse)
            <div class="col-lg-8">
                <div class="placeholder-card">
                    <span class="featured-pill">Featured Course</span>
                    <h3>{{ $featuredCourse->title }}</h3>
                    <p>{{ $featuredCourse->short_description ?: 'Kh&#243;a h&#7885;c &#273;ang &#273;&#432;&#7907;c &#273;&#7863;t &#7903; v&#7883; tr&#237; n&#7893;i b&#7853;t tr&#234;n trang ch&#7911;.' }}</p>
                    @if($featuredCourse->platform || $featuredCourse->level || $featuredCourse->duration_text)
                    <p class="course-placeholder-note">
                        {{ $featuredCourse->platform ?: 'Online' }}
                        @if($featuredCourse->level) | {{ $featuredCourse->level }} @endif
                        @if($featuredCourse->duration_text) | {{ $featuredCourse->duration_text }} @endif
                    </p>
                    @endif
                    <p class="course-placeholder-note">
                        @if(!is_null($featuredCourse->sale_price))
                            Gi&#225; &#432;u &#273;&#227;i: {{ number_format($featuredCourse->sale_price, 0, ',', '.') }} VND
                        @elseif(!is_null($featuredCourse->price))
                            Gi&#225;: {{ number_format($featuredCourse->price, 0, ',', '.') }} VND
                        @else
                            Li&#234;n h&#7879; &#273;&#7875; nh&#7853;n t&#432; v&#7845;n h&#7885;c ph&#237;.
                        @endif
                    </p>
                    <a href="{{ route('course.detail', $featuredCourse->slug) }}" class="theme-btn">Xem chi ti&#7871;t <i class="fa-solid fa-arrow-up-right"></i></a>
                    @if($featuredCourse->cta_link)
                    <a href="{{ $featuredCourse->cta_link }}" class="theme-btn border-btn">{{ $featuredCourse->cta_text ?: '&#272;&#259;ng k&#253; h&#7885;c' }} <i class="fa-solid fa-arrow-up-right"></i></a>
                    @else
                    <a href="{{ route('contact', ['course' => $featuredCourse->slug]) }}" class="theme-btn border-btn">Li&#234;n h&#7879; t&#432; v&#7845;n <i class="fa-solid fa-arrow-up-right"></i></a>
                    @endif
                </div>
            </div>
            <div class="col-lg-4">
                <div class="placeholder-card">
                    <h3>Kh&#243;a h&#7885;c kh&#225;c</h3>
                    @forelse($highlightCourses as $courseItem)
                    <div class="course-side-card">
                        <h4><a href="{{ route('course.detail', $courseItem->slug) }}">{{ $courseItem->title }}</a></h4>
                        <p>{{ $courseItem->short_description ?: 'Xem chi ti&#7871;t &#273;&#7875; bi&#7871;t n&#7897;i dung v&#224; l&#7897; tr&#236;nh h&#7885;c.' }}</p>
                        <p class="course-placeholder-note">
                            @if(!is_null($courseItem->sale_price))
                                {{ number_format($courseItem->sale_price, 0, ',', '.') }} VND
                            @elseif(!is_null($courseItem->price))
                                {{ number_format($courseItem->price, 0, ',', '.') }} VND
                            @else
                                Li&#234;n h&#7879;
                            @endif
                        </p>
                    </div>
                    @empty
                    <p>Ch&#432;a c&#243; th&#234;m kh&#243;a h&#7885;c n&#224;o kh&#225;c &#273;ang hi&#7875;n th&#7883;.</p>
                    @endforelse
                    <p class="course-placeholder-note">B&#7841;n c&#243; th&#7875; xem to&#224;n b&#7897; kh&#243;a h&#7885;c &#273;ang active t&#7841;i trang danh s&#225;ch.</p>
                    <a href="{{ route('courses') }}" class="theme-btn">Xem t&#7845;t c&#7843; kh&#243;a h&#7885;c <i class="fa-solid fa-arrow-up-right"></i></a>
                </div>
            </div>
            @else
            <div class="col-lg-4">
                <div class="placeholder-card">
                    <h3>Ch&#432;a c&#243; kh&#243;a h&#7885;c n&#7893;i b&#7853;t</h3>
                    <p>Section n&#224;y &#273;ang ch&#7901; d&#7919; li&#7879;u th&#7853;t t&#7915; admin courses.</p>
                    <p class="course-placeholder-note"><a href="{{ route('courses') }}">Xem trang kh&#243;a h&#7885;c</a> ho&#7863;c li&#234;n h&#7879; &#273;&#7875; &#273;&#432;&#7907;c t&#432; v&#7845;n s&#7899;m.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<section class="section-shell pt-0">
    <div class="container">
        <div class="section-title">
            <h6>Case study</h6>
            <h2>K&#7871;t qu&#7843; th&#7921;c t&#7871; v&#224; h&#236;nh &#7843;nh d&#7921; &#225;n &#273;ang gi&#250;p website t&#259;ng &#273;&#7897; tin c&#7853;y</h2>
        </div>
        <div class="row g-4 mt-4">
            @forelse ($cases as $row)
            <div class="col-lg-4">
                <div class="case-card">
                    <img src="{{ $row->image ?: 'app/assets/images/others/thumb-16.jpg' }}" alt="{{ $row->title ?: 'case-study' }}">
                    <div class="content">
                        <h3>{{ $row->title ?: 'Case study th&#7921;c t&#7871;' }}</h3>
                        <p>{{ $row->description ?: 'B&#7841;n c&#243; th&#7875; d&#249;ng module Image c&#361; &#273;&#7875; &#273;&#432;a &#7843;nh d&#7921; &#225;n, k&#7871;t qu&#7843;, workshop ho&#7863;c feedback th&#7921;c t&#7871;.' }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-lg-12">
                <div class="placeholder-card">
                    <h3>Case study sẽ được cập nhật sớm</h3>
                    <p>Các kết quả và hình ảnh dự án thực tế đang được bổ sung. Để lại thông tin để được tư vấn ngay.</p>
                </div>
            </div>
            @endforelse
        </div>
        <div class="cta-inline">
            <a href="{{ route('portfolio') }}" class="theme-btn">Xem to&#224;n b&#7897; case study <i class="fa-solid fa-arrow-up-right"></i></a>
        </div>
    </div>
</section>

<section class="section-shell pt-0">
    <div class="container">
        <div class="section-title">
            <h6>Blog n&#7893;i b&#7853;t</h6>
            <h2>Gi&#7919; l&#7841;i blog c&#361; v&#224; bi&#7871;n n&#243; th&#224;nh k&#234;nh x&#226;y d&#7921;ng ni&#7873;m tin</h2>
        </div>
        <div class="row g-4 mt-4">
            @foreach ($blogs as $row)
            <div class="col-lg-4">
                <div class="blog-mini-card">
                    <img src="{{ $row->image_url }}" alt="{{ $row->title }}">
                    <div class="content">
                        <span class="post-date">{{ optional($row->created_at)->format('d/m/Y') }}</span>
                        <h3><a href="{{ route('blog', $row->slug) }}">{{ $row->title }}</a></h3>
                        <p>{{ $row->description }}</p>
                        <a href="{{ route('blog', $row->slug) }}">&#272;&#7885;c b&#224;i vi&#7871;t <i class="fa-solid fa-arrow-up-right"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section-shell pt-0">
    <div class="container">
        <div class="section-title">
            <h6>Feedback h&#7885;c vi&#234;n / kh&#225;ch h&#224;ng</h6>
            <h2>Nh&#7853;n x&#233;t th&#7921;c t&#7871; gi&#250;p t&#259;ng &#273;&#7897; tin c&#7853;y khi &#273;&#259;ng k&#253; t&#432; v&#7845;n</h2>
        </div>
        <div class="row g-4 mt-4">
            @forelse($featuredTestimonials as $testimonial)
            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card">
                    @if($testimonial->rating)
                    <div class="testimonial-rating">
                        @for($i = 0; $i < $testimonial->rating; $i++)
                            <i class="fa-solid fa-star"></i>
                        @endfor
                    </div>
                    @endif
                    <p>{{ $testimonial->content }}</p>
                    <div class="testimonial-meta">
                        <img class="testimonial-avatar" src="{{ $testimonial->avatar ?: 'app/assets/images/others/thumb-16.jpg' }}" alt="{{ $testimonial->name }}">
                        <div>
                            <p class="testimonial-name">{{ $testimonial->name }}</p>
                            <p class="testimonial-role">
                                {{ $testimonial->job_title ?: 'H&#7885;c vi&#234;n / Kh&#225;ch h&#224;ng' }}
                                @if($testimonial->company) - {{ $testimonial->company }} @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="placeholder-card">
                    <h3>&#272;ang c&#7853;p nh&#7853;t feedback</h3>
                    <p>Ch&#250;ng t&#244;i &#273;ang b&#7893; sung th&#234;m testimonial. B&#7841;n c&#243; th&#7875; xem case study ho&#7863;c &#273;&#7875; l&#7841;i th&#244;ng tin &#273;&#7875; &#273;&#432;&#7907;c t&#432; v&#7845;n s&#7899;m.</p>
                    <a href="#final-cta" class="theme-btn">&#272;&#259;ng k&#253; t&#432; v&#7845;n <i class="fa-solid fa-arrow-up-right"></i></a>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<section class="section-shell pt-0">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-5">
                <div class="brand-card">
                    <img src="{{ $about->avatar ?: 'app/assets/images/others/thumb-16.jpg' }}" alt="{{ $about->name }}">
                </div>
            </div>
            <div class="col-lg-7">
                <div class="section-title">
                    <h6>Gi&#7899;i thi&#7879;u b&#7843;n th&#226;n</h6>
                    <h2>X&#226;y d&#7921;ng th&#432;&#417;ng hi&#7879;u c&#225; nh&#226;n &#273;&#7875; b&#225;n kh&#243;a h&#7885;c v&#224; t&#432; v&#7845;n d&#7877; h&#417;n</h2>
                </div>
                <div>{!! $about->about_me ?: $about->content !!}</div>
                <div class="cta-inline">
                    <a href="{{ route('about') }}" class="theme-btn">Xem h&#7891; s&#417; &#273;&#7847;y &#273;&#7911; <i class="fa-solid fa-arrow-up-right"></i></a>
                    <a href="tel:{{ $about->tel }}" class="theme-btn border-btn">G&#7885;i t&#432; v&#7845;n <i class="fa-solid fa-arrow-up-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section section-padding fix pt-0" id="final-cta">
    <div class="shape-1">
        <img src="{{ asset('site/assets/img/home-1/cta/cta-shape-1.png') }}" alt="img">
    </div>
    <div class="shape-2">
        <img src="{{ asset('site/assets/img/home-1/cta/shape-1.png') }}" alt="img">
    </div>
    <div class="shape-3">
        <img src="{{ asset('site/assets/img/home-1/cta/shape-2.png') }}" alt="img">
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="cta-text-items text-center">
                <h2 class="text_invert-2">S&#7861;n s&#224;ng &#273;&#432;a th&#432;&#417;ng hi&#7879;u c&#225; nh&#226;n th&#224;nh m&#7897;t website c&#243; th&#7875; b&#225;n kh&#243;a h&#7885;c?</h2>
                <h3 class="footer-big-text wt-about-title2">&#272;&#259;ng k&#253; t&#432; v&#7845;n nhanh</h3>
                <div class="wow fadeInUp" data-wow-delay=".3s">
                    <p class="course-placeholder-note">Nh&#7853;n t&#432; v&#7845;n nhanh qua &#273;i&#7879;n tho&#7841;i, Zalo ho&#7863;c trang li&#234;n h&#7879;.</p>
                    @if(session('success'))
                        <div class="lead-feedback success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="lead-feedback error">{{ $errors->first() }}</div>
                    @endif
                    <form class="lead-form-shell" action="{{ route('lead.store') }}" method="POST">
                        @csrf
                        {{-- Honeypot chống bot: bot điền, user thật không thấy. --}}
                        <div class="hp-wrap" aria-hidden="true">
                            <label for="hp-website-home">Website</label>
                            <input type="text" name="website" id="hp-website-home" tabindex="-1" autocomplete="off">
                        </div>
                        <input type="hidden" name="source_page" value="home_final_cta">
                        <div class="form-row">
                            <input type="text" name="name" placeholder="H&#7885; v&#224; t&#234;n (*)" value="{{ old('name') }}" required>
                            <input type="text" name="phone" placeholder="S&#7889; &#273;i&#7879;n tho&#7841;i (*)" value="{{ old('phone') }}" required>
                        </div>
                        <div class="form-row single">
                            <input type="email" name="email" placeholder="Email (kh&#244;ng b&#7855;t bu&#7897;c)" value="{{ old('email') }}">
                        </div>
                        <div class="form-row single">
                            <textarea name="message" placeholder="Nhu c&#7847;u c&#7911;a b&#7841;n (kh&#244;ng b&#7855;t bu&#7897;c)">{{ old('message') }}</textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="theme-btn">&#272;&#259;ng k&#253; t&#432; v&#7845;n <i class="fa-solid fa-arrow-up-right"></i></button>
                        </div>
                        <p class="form-note">Ch&#250;ng t&#244;i s&#7869; li&#234;n h&#7879; trong gi&#7901; l&#224;m vi&#7879;c.</p>
                    </form>
                    <div class="cta-inline justify-content-center">
                        <a href="{{ route('courses') }}" class="theme-btn">Xem t&#7845;t c&#7843; kh&#243;a h&#7885;c <i class="fa-solid fa-arrow-up-right"></i></a>
                        <a href="{{ route('contact') }}" class="theme-btn border-btn">Li&#234;n h&#7879; t&#432; v&#7845;n <i class="fa-solid fa-arrow-up-right"></i></a>
                        <a href="tel:{{ $about->tel }}" class="theme-btn border-btn">G&#7885;i ngay <i class="fa-solid fa-arrow-up-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

{{-- Đẩy typing effect xuống cuối body (sau khi jQuery local nạp) để tránh $ undefined. --}}
@push('scripts')
<script>
$(document).ready(function () {
    const $el = $("#typing-text");
    if (!$el.length) return;

    const words = @json($words);
    if (!words.length) return;

    let index = 0;
    let letterIndex = 0;
    let isDeleting = false;
    let interval;

    function typeEffect() {
        const currentWord = words[index];
        if (!isDeleting && letterIndex <= currentWord.length) {
            $el.text(currentWord.substring(0, letterIndex));
            letterIndex++;
        } else if (isDeleting && letterIndex >= 0) {
            $el.text(currentWord.substring(0, letterIndex));
            letterIndex--;
        }

        if (letterIndex > currentWord.length) {
            isDeleting = true;
            clearInterval(interval);
            interval = setInterval(typeEffect, 100);
        } else if (letterIndex < 0) {
            isDeleting = false;
            index = (index + 1) % words.length;
            clearInterval(interval);
            interval = setInterval(typeEffect, 150);
        }
    }

    interval = setInterval(typeEffect, 150);
});
</script>
@endpush
