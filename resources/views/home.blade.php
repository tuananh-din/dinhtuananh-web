@extends('layouts.master')

@push('structured_data')
    @include('partials.jsonld-person', ['about' => $about])
@endpush

@section('content')
@php($heroWord = $words->first() ?? 'mục tiêu của bạn')

<section class="hero-section hero-1 hero-section1 fix">
    <div class="line-shape" aria-hidden="true">
        <img src="{{ asset('site/assets/img/home-1/hero/line-shape.png') }}" alt="">
    </div>
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-xl-7">
                <div class="hero-content">
                    <span class="brand-eyebrow wow fadeInUp">Đào tạo Digital Marketing thực hành</span>
                    <h1 class="brand-hero-title wow fadeInUp" data-wow-delay=".2s">
                        <span class="title-name">Học Digital Marketing</span>
                        <span class="title-sub">bắt đầu từ nội dung</span>
                        <strong id="typing-text">{{ $heroWord }}</strong>
                    </h1>
                    <p class="homepage-hero-intro wow fadeInUp" data-wow-delay=".3s">Khám phá khóa học, nội dung chuyên môn và phương án tư vấn phù hợp với nhu cầu hiện tại của bạn.</p>
                    <ul class="hero-bullets wow fadeInUp" data-wow-delay=".4s">
                        <li>Xem thông tin khóa học trước khi quyết định để lại nhu cầu.</li>
                        <li>Đọc nội dung và dự án đã được công bố để hiểu thêm cách triển khai.</li>
                        <li>Gửi mục tiêu của bạn để nhận tư vấn hướng đi phù hợp.</li>
                    </ul>
                    <div class="cta-inline homepage-hero-actions wow fadeInUp" data-wow-delay=".5s">
                        <a href="#courses" class="theme-btn">Xem khóa học phù hợp <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>
                        <a href="#final-cta" class="theme-btn border-btn">Nhận tư vấn lộ trình <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-5">
                <aside class="brand-card hero-profile-card wow fadeInUp" data-wow-delay=".3s" aria-label="Cách bắt đầu hành trình học">
                    <div class="hero-image hero-profile-image image-wrapper">
                        <img class="animated-image" src="{{ $about->avatar ?: 'app/assets/images/others/thumb-16.jpg' }}" alt="{{ $about->name ?: 'Tuấn Anh' }}" width="600" height="680" fetchpriority="high" decoding="async">
                    </div>
                    <div class="homepage-hero-guide">
                        <p class="homepage-hero-guide__eyebrow">Bắt đầu từ điều bạn cần</p>
                        <ol>
                            <li><span>01</span> Xem lộ trình và thông tin khóa học.</li>
                            <li><span>02</span> Kiểm tra nội dung, case study và bài viết đã công bố.</li>
                            <li><span>03</span> Gửi nhu cầu để trao đổi hướng phù hợp.</li>
                        </ol>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>

<section class="section-shell homepage-paths" aria-labelledby="paths-title">
    <div class="container">
        <div class="section-title text-center">
            <p class="type-eyebrow">Bắt đầu theo mục tiêu của bạn</p>
            <h2 id="paths-title">Bạn đang cần hỗ trợ theo hướng nào?</h2>
        </div>
        <div class="row g-4 mt-4">
            <div class="col-lg-4">
                <a class="homepage-path-card" href="#courses">
                    <span class="homepage-path-card__icon"><i class="fa-solid fa-graduation-cap" aria-hidden="true"></i></span>
                    <h3>Tìm khóa học phù hợp</h3>
                    <p>Xem nội dung, hình thức và thông tin hiện có của từng khóa trước khi đăng ký.</p>
                    <span class="homepage-path-card__link">Xem khóa học <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></span>
                </a>
            </div>
            <div class="col-lg-4">
                <a class="homepage-path-card" href="#final-cta">
                    <span class="homepage-path-card__icon"><i class="fa-solid fa-comments" aria-hidden="true"></i></span>
                    <h3>Trao đổi nhu cầu của bạn</h3>
                    <p>Mô tả ngắn mục tiêu hoặc vướng mắc để nhận tư vấn hướng đi phù hợp.</p>
                    <span class="homepage-path-card__link">Nhận tư vấn <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></span>
                </a>
            </div>
            <div class="col-lg-4">
                <a class="homepage-path-card" href="{{ ($cases->isNotEmpty() || $blogs->isNotEmpty()) ? '#evidence' : route('about') }}">
                    <span class="homepage-path-card__icon"><i class="fa-solid fa-book-open" aria-hidden="true"></i></span>
                    <h3>{{ ($cases->isNotEmpty() || $blogs->isNotEmpty()) ? 'Xem nội dung đã công bố' : 'Tìm hiểu về người đồng hành' }}</h3>
                    <p>{{ ($cases->isNotEmpty() || $blogs->isNotEmpty()) ? 'Đọc bài viết chuyên môn và xem dự án đã được xuất bản trước khi liên hệ.' : 'Tìm hiểu thêm về định hướng và kinh nghiệm được giới thiệu trên website.' }}</p>
                    <span class="homepage-path-card__link">{{ ($cases->isNotEmpty() || $blogs->isNotEmpty()) ? 'Xem minh chứng' : 'Xem giới thiệu' }} <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></span>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="section-shell homepage-courses" id="courses" aria-labelledby="courses-title">
    <div class="container">
        <div class="section-title">
            <p class="type-eyebrow">Khóa học</p>
            <h2 id="courses-title">Chọn khóa học theo mục tiêu hiện tại</h2>
            <p class="homepage-section-lead">Xem thông tin từng khóa để tự đánh giá mức phù hợp trước khi để lại nhu cầu.</p>
        </div>
        <div class="row g-4 mt-4 home-featured-courses {{ !$featuredCourse ? 'home-featured-courses--empty' : ($highlightCourses->isEmpty() ? 'home-featured-courses--single' : '') }}">
            @if($featuredCourse)
            <div class="col-lg-8">
                <article class="placeholder-card homepage-course-featured">
                    <span class="featured-pill">Khóa học nổi bật</span>
                    <h3>{{ $featuredCourse->title }}</h3>
                    <p>{{ $featuredCourse->short_description ?: 'Xem chi tiết để biết nội dung và lộ trình học.' }}</p>
                    @if($featuredCourse->platform || $featuredCourse->level || $featuredCourse->duration_text)
                    <ul class="homepage-course-meta" aria-label="Thông tin khóa học">
                        @if($featuredCourse->platform)<li><i class="fa-solid fa-display" aria-hidden="true"></i>{{ $featuredCourse->platform }}</li>@endif
                        @if($featuredCourse->level)<li><i class="fa-solid fa-layer-group" aria-hidden="true"></i>{{ $featuredCourse->level }}</li>@endif
                        @if($featuredCourse->duration_text)<li><i class="fa-solid fa-clock" aria-hidden="true"></i>{{ $featuredCourse->duration_text }}</li>@endif
                    </ul>
                    @endif
                    <p class="course-placeholder-note">
                        @if(!is_null($featuredCourse->sale_price))
                            Học phí hiện tại: {{ number_format($featuredCourse->sale_price, 0, ',', '.') }} VND
                        @elseif(!is_null($featuredCourse->price))
                            Học phí hiện tại: {{ number_format($featuredCourse->price, 0, ',', '.') }} VND
                        @else
                            Liên hệ để nhận tư vấn học phí.
                        @endif
                    </p>
                    <div class="cta-inline">
                        <a href="{{ route('course.detail', $featuredCourse->slug) }}" class="theme-btn">Xem nội dung khóa học <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>
                        @if($featuredCourse->cta_link)
                        <a href="{{ $featuredCourse->cta_link }}" class="theme-btn border-btn">{{ $featuredCourse->cta_text ?: 'Đăng ký học' }} <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>
                        @else
                        <a href="{{ route('contact', ['course' => $featuredCourse->slug]) }}" class="theme-btn border-btn">Nhận tư vấn về khóa này <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>
                        @endif
                    </div>
                </article>
            </div>
            @if($highlightCourses->isNotEmpty())
            <div class="col-lg-4">
                <div class="placeholder-card homepage-course-list">
                    <h3>Khóa học khác</h3>
                    @foreach($highlightCourses as $courseItem)
                    <article class="course-side-card">
                        <h4><a href="{{ route('course.detail', $courseItem->slug) }}">{{ $courseItem->title }}</a></h4>
                        <p>{{ $courseItem->short_description ?: 'Xem chi tiết để biết nội dung và lộ trình học.' }}</p>
                        @if(!is_null($courseItem->sale_price) || !is_null($courseItem->price))
                        <p class="course-placeholder-note">Học phí hiện tại: {{ number_format($courseItem->sale_price ?? $courseItem->price, 0, ',', '.') }} VND</p>
                        @endif
                        <a href="{{ route('course.detail', $courseItem->slug) }}" class="theme-btn border-btn">Xem nội dung <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>
                    </article>
                    @endforeach
                    <a href="{{ route('courses') }}" class="theme-btn">Xem tất cả khóa học <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>
                </div>
            </div>
            @endif
            @else
            <div class="col-lg-8">
                <div class="placeholder-card homepage-empty-card">
                    <h3>Hiện chưa có khóa học để hiển thị</h3>
                    <p>Hãy để lại mục tiêu học để nhận tư vấn nội dung phù hợp.</p>
                    <a href="#final-cta" class="theme-btn">Gửi nhu cầu tư vấn <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<section class="choose-us-section fix section-shell homepage-support" aria-labelledby="support-title">
    <div class="container">
        <div class="section-title">
            <p class="type-eyebrow">Cách đồng hành</p>
            <h2 id="support-title">Khám phá chủ đề phù hợp với cách bạn đang học hoặc triển khai</h2>
            <p class="homepage-section-lead">Các nội dung bên dưới được hiển thị từ thông tin quản trị của website.</p>
        </div>
        @if($jobs->isNotEmpty())
        <div class="row g-4 mt-4 homepage-support-grid">
            @php($serviceIcons = ['fa-bullhorn', 'fa-chart-line', 'fa-laptop-code'])
            @foreach ($jobs as $key => $row)
            <div class="col-lg-4">
                <article class="brand-card homepage-support-card wow fadeInUp" data-wow-delay=".{{ ($key + 1) * 2 }}s">
                    <span class="homepage-support-card__icon"><i class="fa-solid {{ $serviceIcons[$key % count($serviceIcons)] }}" aria-hidden="true"></i></span>
                    <h3>{{ $row->title }}</h3>
                    <p>{{ $row->description }}</p>
                </article>
            </div>
            @endforeach
        </div>
        @else
        <div class="brand-card homepage-empty-card mt-4">
            <h3>Nội dung đang được cập nhật</h3>
            <p>Gửi mục tiêu của bạn để nhận tư vấn hướng phù hợp.</p>
        </div>
        @endif
        <div class="cta-inline homepage-support-cta">
            <a href="#final-cta" class="theme-btn">Trao đổi nhu cầu <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>
        </div>
    </div>
</section>

@if($usesCaseStudies || $cases->isNotEmpty() || $blogs->isNotEmpty())
<section class="section-shell homepage-evidence" id="evidence" aria-labelledby="evidence-title">
    <div class="container">
        <div class="section-title">
            <p class="type-eyebrow">Nội dung &amp; minh chứng</p>
            <h2 id="evidence-title">Tìm hiểu trước khi quyết định</h2>
            <p class="homepage-section-lead">Xem nội dung và hình ảnh đã được công bố trên website.</p>
        </div>
        @if($usesCaseStudies)
        <div class="homepage-evidence-block">
            <div class="homepage-evidence-block__heading">
                <div><h3>Dự án đã công bố</h3><p>Khám phá từng dự án để xem phạm vi và cách triển khai được chia sẻ.</p></div>
                <a href="{{ route('portfolio') }}" class="theme-btn border-btn">Xem case study <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>
            </div>
            <div class="row g-4">
                @foreach ($cases as $row)
                <div class="col-lg-4">
                    <article class="case-card homepage-evidence-card">
                        <a href="{{ route('portfolio.detail', $row->slug) }}" aria-label="Xem case study {{ $row->title }}">
                            <img src="{{ $row->image_url }}" alt="{{ $row->title }}" width="640" height="426" loading="lazy" decoding="async">
                            <div class="content">
                                @if($row->industry)<span class="case-card__industry">{{ $row->industry }}</span>@endif
                                <h4>{{ $row->title }}</h4>
                                <p>{{ $row->summary ?: 'Xem chi tiết dự án đã được công bố.' }}</p>
                                <span class="homepage-evidence-card__link">Xem chi tiết <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></span>
                            </div>
                        </a>
                    </article>
                </div>
                @endforeach
            </div>
        </div>
        @elseif($cases->isNotEmpty())
        <div class="homepage-evidence-block">
            <div class="homepage-evidence-block__heading">
                <div><h3>Hình ảnh hoạt động</h3><p>Một số hình ảnh được chia sẻ trên website.</p></div>
                <a href="{{ route('portfolio') }}" class="theme-btn border-btn">Xem thêm hình ảnh <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>
            </div>
            <div class="row g-4">
                @foreach ($cases as $row)
                <div class="col-lg-4">
                    <article class="case-card homepage-evidence-card">
                        <img src="{{ $row->image ?: 'app/assets/images/others/thumb-16.jpg' }}" alt="{{ $row->title ?: 'Hình ảnh hoạt động' }}" width="640" height="426" loading="lazy" decoding="async">
                        <div class="content"><h4>{{ $row->title ?: 'Hình ảnh hoạt động' }}</h4>@if($row->description)<p>{{ $row->description }}</p>@endif</div>
                    </article>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @if($blogs->isNotEmpty())
        <div class="homepage-evidence-block">
            <div class="homepage-evidence-block__heading">
                <div><h3>Bài viết mới</h3><p>Đọc thêm các góc nhìn về Digital Marketing được chia sẻ trên website.</p></div>
                <a href="{{ route('blogs') }}" class="theme-btn border-btn">Xem tất cả bài viết <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>
            </div>
            <div class="row g-4 home-featured-blogs {{ $blogs->count() <= 1 ? 'home-featured-blogs--sparse' : '' }}">
                @foreach ($blogs as $row)
                <div class="col-lg-4">
                    <article class="blog-mini-card homepage-evidence-card">
                        <img src="{{ $row->image_url }}" alt="{{ $row->title }}" width="640" height="426" loading="lazy" decoding="async">
                        <div class="content">
                            <span class="post-date">{{ optional($row->created_at)->format('d/m/Y') }}</span>
                            <h4><a href="{{ route('blog', $row->slug) }}">{{ $row->title }}</a></h4>
                            <p>{{ $row->description }}</p>
                            <a class="homepage-evidence-card__link" href="{{ route('blog', $row->slug) }}">Đọc bài viết <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>
                        </div>
                    </article>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endif

@if($featuredTestimonials->isNotEmpty())
<section class="section-shell homepage-testimonials" aria-labelledby="testimonials-title">
    <div class="container">
        <div class="section-title"><p class="type-eyebrow">Chia sẻ đã công bố</p><h2 id="testimonials-title">Những chia sẻ được hiển thị trên website</h2></div>
        <div class="row g-4 mt-4">
            @foreach($featuredTestimonials as $testimonial)
            <div class="col-lg-4 col-md-6">
                <article class="testimonial-card">
                    @if($testimonial->rating)
                    <div class="testimonial-rating" aria-label="{{ $testimonial->rating }} trên 5 sao">@for($i = 0; $i < $testimonial->rating; $i++)<i class="fa-solid fa-star" aria-hidden="true"></i>@endfor</div>
                    @endif
                    <blockquote>{{ $testimonial->content }}</blockquote>
                    <div class="testimonial-meta">
                        @if($testimonial->avatar)<img class="testimonial-avatar" src="{{ $testimonial->avatar }}" alt="{{ $testimonial->name }}" width="64" height="64" loading="lazy" decoding="async">@endif
                        <div><p class="testimonial-name">{{ $testimonial->name }}</p>@if($testimonial->job_title || $testimonial->company)<p class="testimonial-role">{{ collect([$testimonial->job_title, $testimonial->company])->filter()->implode(' · ') }}</p>@endif</div>
                    </div>
                </article>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section-shell homepage-about" aria-labelledby="about-title">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-5"><div class="brand-card home-about-image"><img src="{{ $about->avatar ?: 'app/assets/images/others/thumb-16.jpg' }}" alt="{{ $about->name ?: 'Tuấn Anh' }}" width="600" height="680" loading="lazy" decoding="async"></div></div>
            <div class="col-lg-7">
                <div class="section-title home-about-copy"><p class="type-eyebrow">Giới thiệu</p><h2 id="about-title">Tìm hiểu thêm về người đồng hành cùng bạn</h2></div>
                @if(filled($about->about_me) || filled($about->content))<div class="home-about-copy__body">{!! $about->about_me ?: $about->content !!}</div>@endif
                <div class="cta-inline">
                    <a href="{{ route('about') }}" class="theme-btn">Xem hồ sơ đầy đủ <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>
                    @if(filled($about->tel))<a href="tel:{{ $about->tel }}" class="theme-btn border-btn">Gọi tư vấn <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>@endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section section-padding fix pt-0" id="final-cta" aria-labelledby="consultation-title">
    @if($leadMagnet)
    <div class="container"><div class="brand-card lead-magnet-card text-center mb-4">
        <h3>{{ $leadMagnet->name }}</h3><p>{{ $leadMagnet->description }}</p>
        @php($leadMagnetErrors = $errors->getBag('leadMagnet'))
        @if($leadMagnetErrors->any())
            @include('partials.notice-banner', ['type' => 'error', 'title' => 'Vui lòng kiểm tra email', 'messages' => $leadMagnetErrors->all()])
        @endif
        <form method="POST" action="{{ route('lead-magnet.subscribe', $leadMagnet->id) }}" data-submit-label="Đang gửi...">
            @csrf
            <input type="text" name="website" tabindex="-1" autocomplete="off" class="visually-hidden">
            <label for="magnet-email">Nhập email để nhận tài liệu</label>
            <div class="lead-magnet-card__form"><input id="magnet-email" name="email" type="email" required autocomplete="email" placeholder="ten@domain.com" aria-describedby="magnet-email-note"><button class="theme-btn" type="submit">Nhận tài liệu <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></button></div>
            <p id="magnet-email-note" class="form-note">Chúng tôi dùng email này để gửi tài liệu bạn yêu cầu.</p>
        </form>
    </div></div>
    @endif
    <div class="shape-1" aria-hidden="true"><img src="{{ asset('site/assets/img/home-1/cta/cta-shape-1.png') }}" alt=""></div>
    <div class="shape-2" aria-hidden="true"><img src="{{ asset('site/assets/img/home-1/cta/shape-1.png') }}" alt=""></div>
    <div class="shape-3" aria-hidden="true"><img src="{{ asset('site/assets/img/home-1/cta/shape-2.png') }}" alt=""></div>
    <div class="container"><div class="row"><div class="col-xl-12"><div class="cta-text-items text-center">
        <p class="brand-eyebrow">Tư vấn lộ trình</p>
        <h2 id="consultation-title" class="text_invert-2">Chưa chắc nên bắt đầu từ khóa nào?</h2>
        <p class="homepage-final-intro">Để lại mục tiêu học hoặc câu hỏi của bạn để nhận tư vấn hướng phù hợp.</p>
        <div class="wow fadeInUp" data-wow-delay=".3s">
            @php($leadErrors = $errors->getBag('lead'))
            @if(data_get(session('notice'), 'context') === 'lead')
                @include('partials.notice-banner', array_merge(session('notice'), ['dismissible' => true]))
            @endif
            @if($leadErrors->any())
                @include('partials.notice-banner', ['type' => 'error', 'title' => 'Vui lòng kiểm tra lại thông tin', 'messages' => $leadErrors->all()])
            @endif
            <form class="lead-form-shell" action="{{ route('lead.store') }}" method="POST" data-submit-label="Đang gửi...">
                @csrf
                <div class="hp-wrap" aria-hidden="true"><label for="hp-website-home">Website</label><input type="text" name="website" id="hp-website-home" tabindex="-1" autocomplete="off"></div>
                <input type="hidden" name="source_page" value="home_final_cta">
                <div class="form-row">
                    <div class="lead-form-field"><label for="lead-name">Họ và tên <span aria-hidden="true">*</span></label><input id="lead-name" type="text" name="name" value="{{ old('name') }}" autocomplete="name" required></div>
                    <div class="lead-form-field"><label for="lead-phone">Số điện thoại <span aria-hidden="true">*</span></label><input id="lead-phone" type="tel" name="phone" value="{{ old('phone') }}" inputmode="tel" autocomplete="tel" required></div>
                </div>
                <div class="form-row single"><div class="lead-form-field"><label for="lead-email">Email <span class="lead-form-field__optional">Không bắt buộc</span></label><input id="lead-email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="ten@domain.com"></div></div>
                <div class="form-row single"><div class="lead-form-field"><label for="lead-message">Mục tiêu hoặc câu hỏi <span class="lead-form-field__optional">Không bắt buộc</span></label><textarea id="lead-message" name="message">{{ old('message') }}</textarea></div></div>
                <div class="form-actions"><button type="submit" class="theme-btn">Gửi nhu cầu tư vấn <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></button></div>
                <p class="form-note">Thông tin được dùng để liên hệ tư vấn.</p>
            </form>
            <div class="cta-inline justify-content-center">
                <a href="{{ route('courses') }}" class="theme-btn border-btn">Xem tất cả khóa học <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>
                @if(filled($about->tel))<a href="tel:{{ $about->tel }}" class="theme-btn border-btn">Gọi tư vấn <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>@endif
            </div>
        </div>
    </div></div></div></div>
</section>
@endsection

@push('scripts')
<script>
$(function () {
    const el = document.getElementById('typing-text');
    const words = @json($words);
    const canMatchMedia = typeof window.matchMedia === 'function';
    const canUseEnhancedMotion = canMatchMedia
        && !window.matchMedia('(prefers-reduced-motion: reduce)').matches
        && window.matchMedia('(pointer: fine)').matches
        && window.matchMedia('(hover: hover)').matches;

    if (!el || !words.length || !canUseEnhancedMotion || !('IntersectionObserver' in window)) {
        return;
    }

    let index = Math.max(words.indexOf(el.textContent.trim()), 0);
    let letterIndex = words[index].length;
    let isDeleting = true;
    let timer = null;
    let isHeroVisible = false;
    let observer = null;

    function stopTyping() {
        if (timer !== null) {
            window.clearTimeout(timer);
            timer = null;
        }
    }

    function typeNextCharacter() {
        if (!isHeroVisible || document.visibilityState !== 'visible') {
            stopTyping();
            return;
        }

        const currentWord = words[index];
        if (isDeleting) {
            letterIndex--;
            el.textContent = currentWord.substring(0, Math.max(letterIndex, 0));

            if (letterIndex <= 0) {
                isDeleting = false;
                index = (index + 1) % words.length;
            }
        } else {
            letterIndex++;
            el.textContent = currentWord.substring(0, letterIndex);

            if (letterIndex >= currentWord.length) {
                isDeleting = true;
            }
        }

        timer = window.setTimeout(typeNextCharacter, isDeleting ? 100 : 150);
    }

    function startTyping() {
        if (timer !== null || !isHeroVisible || document.visibilityState !== 'visible') {
            return;
        }

        timer = window.setTimeout(typeNextCharacter, 900);
    }

    function syncTypingWithVisibility() {
        if (document.visibilityState === 'visible') {
            startTyping();
        } else {
            stopTyping();
        }
    }

    observer = new IntersectionObserver(function (entries) {
        isHeroVisible = entries.some(function (entry) {
            return entry.isIntersecting;
        });

        if (isHeroVisible) {
            startTyping();
        } else {
            stopTyping();
        }
    }, { threshold: 0.1 });

    observer.observe(el.closest('.hero-section') || el);
    document.addEventListener('visibilitychange', syncTypingWithVisibility);
    window.addEventListener('pagehide', function () {
        stopTyping();
        observer.disconnect();
        document.removeEventListener('visibilitychange', syncTypingWithVisibility);
    }, { once: true });
});
</script>
@endpush
