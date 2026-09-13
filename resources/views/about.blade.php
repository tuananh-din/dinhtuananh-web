@extends('layouts.master')

@php
    $profileImage = filled($image?->image) ? $image->image : (filled($about->avatar) ? $about->avatar : null);
    $siteName = data_get($infor, 'name');
    $profileName = filled($about->name) ? $about->name : (filled($siteName) ? $siteName : 'Tuấn Anh');
    $pageTitle = filled($about->title_seo) ? $about->title_seo : 'Giới thiệu | ' . $profileName;
    $metaSource = filled($about->desc_seo)
        ? $about->desc_seo
        : (filled($about->description) ? $about->description : 'Thông tin giảng dạy và nội dung Digital Marketing của ' . $profileName . '.');
    $metaPlainText = trim(strip_tags($metaSource));
    $metaDescription = \Illuminate\Support\Str::limit($metaPlainText !== '' ? $metaPlainText : 'Thông tin giảng dạy và nội dung Digital Marketing của ' . $profileName . '.', 160, '');
    $profileStory = filled($about->about_me) ? $about->about_me : $about->content;
@endphp

@section('page_title', $pageTitle)
@section('meta_description', $metaDescription)
@section('og_title', $pageTitle)
@section('og_description', $metaDescription)
@section('canonical', route('about'))
@if($profileImage)
    @section('og_image', $profileImage)
@endif

@push('structured_data')
    @include('partials.jsonld-person', ['about' => $about])
@endpush

@section('body_class', 'about-profile-page')

@section('content')
<section class="about-profile-hero section-shell" aria-labelledby="about-profile-title">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="{{ $profileImage ? 'col-lg-7' : 'col-lg-9' }}">
                <p class="about-profile-eyebrow">Giảng dạy &amp; tư vấn Digital Marketing thực hành</p>
                <h1 id="about-profile-title">Học Digital Marketing từ tư duy triển khai, không chỉ từ lý thuyết.</h1>
                <p class="about-profile-hero__lead">Tại đây, bạn có thể xem các khóa học, khám phá nội dung chuyên môn đã công bố và trao đổi về hướng học phù hợp với mục tiêu hiện tại.</p>
                <div class="cta-inline about-profile-actions">
                    <a href="{{ route('courses') }}" class="theme-btn">Xem khóa học <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>
                    <a href="{{ route('contact') }}" class="theme-btn border-btn">Trao đổi lộ trình học <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>
                </div>
            </div>
            @if($profileImage)
            <div class="col-lg-5">
                <figure class="about-profile-portrait">
                    <img src="{{ $profileImage }}" alt="Chân dung {{ $profileName }}" width="720" height="810" fetchpriority="high" loading="eager" decoding="async">
                </figure>
            </div>
            @endif
        </div>
    </div>
</section>

<section class="about-profile-audience section-shell" aria-labelledby="about-audience-title">
    <div class="container">
        <div class="about-profile-section-heading"><p class="about-profile-eyebrow">Bắt đầu từ nhu cầu của bạn</p><h2 id="about-audience-title">Phù hợp với bạn nếu</h2></div>
        <ol class="about-profile-audience-list">
            <li><span>01</span><p>Bạn mới bắt đầu và muốn hiểu cách các phần của Digital Marketing kết nối với nhau.</p></li>
            <li><span>02</span><p>Bạn đang làm marketing và cần hệ thống lại cách đặt mục tiêu, theo dõi dữ liệu và rút kinh nghiệm.</p></li>
            <li><span>03</span><p>Bạn đang điều hành doanh nghiệp nhỏ và muốn trao đổi marketing bằng những tiêu chí rõ ràng hơn.</p></li>
        </ol>
    </div>
</section>

<section class="about-profile-learning section-shell" aria-labelledby="about-learning-title">
    <div class="container">
        <div class="about-profile-section-heading"><p class="about-profile-eyebrow">Nội dung định hướng</p><h2 id="about-learning-title">Bạn có thể học gì ở đây?</h2></div>
        <div class="row g-4 about-profile-learning-grid">
            <div class="col-lg-7"><article class="about-profile-topic about-profile-topic--feature"><span class="about-profile-topic__number">01</span><h3>Xây nền Digital Marketing</h3><p>Hiểu mục tiêu, khách hàng, kênh, nội dung và chỉ số cần theo dõi trước khi bắt đầu triển khai.</p></article></div>
            <div class="col-lg-5 d-grid gap-4"><article class="about-profile-topic"><span class="about-profile-topic__number">02</span><h3>Đọc dữ liệu để ra quyết định</h3><p>Biết đặt câu hỏi, nhận diện tín hiệu và cải thiện kế hoạch thay vì tối ưu theo cảm tính.</p></article><article class="about-profile-topic"><span class="about-profile-topic__number">03</span><h3>Kết nối online với vận hành thực tế</h3><p>Tư duy về hành trình khách hàng, O2O/retail và cách phối hợp triển khai khi nội dung phù hợp với bài toán của bạn.</p></article></div>
        </div>
    </div>
</section>

<section class="about-profile-courses section-shell" aria-labelledby="about-courses-title">
    <div class="container">
        <div class="about-profile-section-heading"><p class="about-profile-eyebrow">Khóa học</p><h2 id="about-courses-title">Khóa học hiện có</h2><p>Xem thông tin từng khóa để tự đánh giá mức phù hợp trước khi đăng ký.</p></div>
        @if($courses->isNotEmpty())
        <div class="row g-4 about-profile-course-grid {{ $courses->count() === 1 ? 'about-profile-course-grid--single' : '' }}">
            @foreach($courses as $course)
            <div class="{{ $loop->first && $courses->count() > 1 ? 'col-lg-7' : 'col-lg-5' }}"><article class="about-profile-course-card {{ $loop->first ? 'about-profile-course-card--feature' : '' }}">
                <h3>{{ $course->title }}</h3>
                @if($course->short_description)<p>{{ $course->short_description }}</p>@endif
                @if($course->platform || $course->level || $course->duration_text)<ul class="about-profile-course-meta" aria-label="Thông tin khóa học">@if($course->platform)<li><i class="fa-solid fa-display" aria-hidden="true"></i>{{ $course->platform }}</li>@endif @if($course->level)<li><i class="fa-solid fa-layer-group" aria-hidden="true"></i>{{ $course->level }}</li>@endif @if($course->duration_text)<li><i class="fa-solid fa-clock" aria-hidden="true"></i>{{ $course->duration_text }}</li>@endif</ul>@endif
                @if(!is_null($course->sale_price) || !is_null($course->price))<p class="about-profile-course-price">Học phí hiện tại: {{ number_format($course->sale_price ?? $course->price, 0, ',', '.') }} VND</p>@endif
                <a href="{{ route('course.detail', $course->slug) }}" class="theme-btn border-btn">Xem nội dung khóa học <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>
            </article></div>
            @endforeach
        </div>
        <div class="cta-inline about-profile-section-cta"><a href="{{ route('courses') }}" class="theme-btn">Xem tất cả khóa học <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a></div>
        @else
        <div class="about-profile-empty"><h3>Hiện chưa có khóa học để hiển thị.</h3><p>Bạn có thể để lại mục tiêu để trao đổi lộ trình phù hợp.</p><a href="{{ route('contact') }}" class="theme-btn">Trao đổi lộ trình học <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a></div>
        @endif
    </div>
</section>

@if($caseStudies->isNotEmpty() || $blogs->isNotEmpty())
<section class="about-profile-evidence section-shell" aria-labelledby="about-evidence-title">
    <div class="container">
        <div class="about-profile-section-heading"><p class="about-profile-eyebrow">Nội dung đã công bố</p><h2 id="about-evidence-title">Tìm hiểu trước khi quyết định</h2><p>Khám phá các dự án và bài viết đang được hiển thị công khai trên website.</p></div>
        @if($caseStudies->isNotEmpty())
        <div class="about-profile-evidence-group"><div class="about-profile-evidence-heading"><h3>Case study</h3><a href="{{ route('portfolio') }}" class="about-profile-text-link">Xem tất cả case study <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a></div><div class="row g-4">
            @foreach($caseStudies as $caseStudy)
            <div class="col-lg-6"><article class="about-profile-evidence-card">@if($caseStudy->image)<img src="{{ $caseStudy->image_url }}" alt="{{ $caseStudy->title }}" width="640" height="426" loading="lazy" decoding="async">@endif<div><p class="about-profile-card-label">{{ $caseStudy->industry ?: 'Case study' }}</p><h4>{{ $caseStudy->title }}</h4>@if($caseStudy->summary)<p>{{ $caseStudy->summary }}</p>@endif<a href="{{ route('portfolio.detail', $caseStudy->slug) }}" class="about-profile-text-link">Xem case study <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a></div></article></div>
            @endforeach
        </div></div>
        @endif
        @if($blogs->isNotEmpty())
        <div class="about-profile-evidence-group"><div class="about-profile-evidence-heading"><h3>Bài viết</h3><a href="{{ route('blogs') }}" class="about-profile-text-link">Xem tất cả bài viết <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a></div><div class="row g-4">
            @foreach($blogs as $blog)
            <div class="col-lg-6"><article class="about-profile-evidence-card">@if($blog->image)<img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" width="640" height="426" loading="lazy" decoding="async">@endif<div><p class="about-profile-card-label">{{ optional($blog->created_at)->format('d/m/Y') }}</p><h4>{{ $blog->title }}</h4>@if($blog->description)<p>{{ $blog->description }}</p>@endif<a href="{{ route('blog', $blog->slug) }}" class="about-profile-text-link">Đọc bài viết <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a></div></article></div>
            @endforeach
        </div></div>
        @endif
    </div>
</section>
@endif

@if(filled($about->description) || filled($profileStory))
<section class="about-profile-story section-shell" aria-labelledby="about-story-title">
    <div class="container"><div class="row justify-content-center"><div class="col-xl-9"><div class="about-profile-section-heading"><p class="about-profile-eyebrow">Giới thiệu</p><h2 id="about-story-title">Câu chuyện nghề nghiệp và lý do chia sẻ</h2></div>@if(filled($about->description))<p class="about-profile-story__lead">{{ $about->description }}</p>@endif @if(filled($profileStory))<div class="about-profile-story__body">{!! $profileStory !!}</div>@endif</div></div></div>
</section>
@endif

<section class="about-profile-final-cta section-shell" aria-labelledby="about-final-cta-title">
    <div class="container"><div class="about-profile-final-cta__inner text-center"><p class="about-profile-eyebrow">Bắt đầu từ điều bạn cần</p><h2 id="about-final-cta-title">Chưa rõ nên bắt đầu từ nội dung nào?</h2><p>Xem thông tin từng khóa học hoặc gửi mục tiêu hiện tại để chọn hướng học phù hợp.</p><div class="cta-inline justify-content-center"><a href="{{ route('courses') }}" class="theme-btn">Xem khóa học <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a><a href="{{ route('contact') }}" class="theme-btn border-btn">Trao đổi lộ trình học <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a></div></div></div>
</section>
@endsection
