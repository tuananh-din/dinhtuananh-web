@extends('layouts.master')
@php
    // A-4: SEO cho từng khoá học. Ưu tiên seo_title/seo_description do admin nhập,
    // fallback dần short_description → description → content (155 ký tự).
    $courseTitle = $course->seo_title ?: $course->title;
    $courseDescription = \Illuminate\Support\Str::limit(
        strip_tags(
            $course->seo_description
                ?: ($course->short_description ?: ($course->description ?: ($course->content ?? '')))
        ),
        155
    );
    $courseBrand = data_get($infor, 'name', 'Personal Brand');
@endphp
@section('page_title', $courseTitle . ' | ' . $courseBrand)
@section('meta_description', $courseDescription)
@section('og_title', $courseTitle)
@section('og_description', $courseDescription)
@if(!empty($course->thumbnail))
    @section('og_image', \Illuminate\Support\Str::startsWith($course->thumbnail, ['http://','https://','//']) ? $course->thumbnail : asset(ltrim($course->thumbnail, '/')))
@endif
@push('structured_data')
    @include('partials.jsonld-course', ['course' => $course])
    @include('partials.jsonld-breadcrumbs', ['items' => [
        ['name' => 'Trang chủ', 'url' => route('index')],
        ['name' => 'Khóa học', 'url' => route('courses')],
        ['name' => $course->title, 'url' => url()->current()],
    ]])
@endpush
@section('content')
<section class="course-detail-shell fix">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="course-main-card">
                    <p class="course-placeholder-note">Kh&#243;a h&#7885;c</p>
                    <h1>{{ $course->title }}</h1>

                    @if($course->short_description)
                    <p>{{ $course->short_description }}</p>
                    @endif

                    @if($course->thumbnail)
                    <p class="mt-4">
                        <span class="course-detail-thumbnail">
                            <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" loading="lazy" style="max-width:100%;height:auto;border-radius:16px;">
                        </span>
                    </p>
                    @endif

                    @if($course->description)
                    <div class="mt-4">
                        <h3>M&#244; t&#7843; kh&#243;a h&#7885;c</h3>
                        <p>{{ $course->description }}</p>
                    </div>
                    @endif

                    @if($course->content)
                    <div class="mt-4">
                        <h3>N&#7897;i dung chi ti&#7871;t</h3>
                        <div>{!! $course->content !!}</div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-4">
                <div class="course-side-card">
                    <h3>Th&#244;ng tin nhanh</h3>

                    <div class="course-price-box">
                        @if(!is_null($course->sale_price))
                            @if(!is_null($course->price))
                                <span class="old">{{ number_format($course->price, 0, ',', '.') }} VND</span>
                            @endif
                            <strong>{{ number_format($course->sale_price, 0, ',', '.') }} VND</strong>
                        @elseif(!is_null($course->price))
                            <strong>{{ number_format($course->price, 0, ',', '.') }} VND</strong>
                        @else
                            <strong>Li&#234;n h&#7879; &#273;&#7875; nh&#7853;n t&#432; v&#7845;n h&#7885;c ph&#237;</strong>
                        @endif
                    </div>

                    <ul class="course-meta-list">
                        <li><strong>N&#7873;n t&#7843;ng:</strong> {{ $course->platform ?: 'Đa nền tảng' }}</li>
                        <li><strong>C&#7845;p &#273;&#7897;:</strong> {{ $course->level ?: 'Phù hợp người mới' }}</li>
                        <li><strong>Th&#7901;i l&#432;&#7907;ng:</strong> {{ $course->duration_text ?: 'Theo l&#7897; tr&#236;nh th&#7921;c t&#7871;' }}</li>
                        <li><strong>H&#236;nh th&#7913;c:</strong> {{ $course->format ?: 'Online / t&#432; v&#7845;n tr&#7921;c ti&#7871;p' }}</li>
                    </ul>

                    <div class="cta-stack">
                        @if($course->cta_link)
                        <a href="{{ $course->cta_link }}" class="theme-btn">
                            {{ $course->cta_text ?: 'Đăng ký học ngay' }}
                            <i class="fa-solid fa-arrow-up-right"></i>
                        </a>
                        @else
                        <a href="{{ route('contact', ['course' => $course->slug]) }}" class="theme-btn">
                            Li&#234;n h&#7879; t&#432; v&#7845;n
                            <i class="fa-solid fa-arrow-up-right"></i>
                        </a>
                        @endif
                        <a href="{{ route('courses') }}" class="theme-btn border-btn">
                            Xem kh&#243;a h&#7885;c kh&#225;c
                            <i class="fa-solid fa-arrow-up-right"></i>
                        </a>
                    </div>

                    @if($testimonials->isNotEmpty())
                    <div class="course-testimonials mt-4">
                        <h4>Học viên nói gì</h4>
                        @foreach($testimonials as $testimonial)
                        <div class="testimonial-card course-testimonial-card">
                            @if($testimonial->rating)
                            <div class="testimonial-rating" aria-label="{{ $testimonial->rating }} trên 5 sao">
                                @for($i = 0; $i < $testimonial->rating; $i++)<i class="fa-solid fa-star" aria-hidden="true"></i>@endfor
                            </div>
                            @endif
                            <p>{{ $testimonial->content }}</p>
                            <div class="testimonial-meta">
                                <img class="testimonial-avatar" src="{{ $testimonial->avatar ?: 'app/assets/images/others/thumb-16.jpg' }}" alt="{{ $testimonial->name }}">
                                <div>
                                    <p class="testimonial-name">{{ $testimonial->name }}</p>
                                    <p class="testimonial-role">{{ $testimonial->job_title ?: 'Học viên / Khách hàng' }}@if($testimonial->company) - {{ $testimonial->company }}@endif</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="lead-form-box">
                        <h4>&#272;&#7875; l&#7841;i th&#244;ng tin t&#432; v&#7845;n</h4>
                        @if(session('success'))
                            <p style="color:#7af2a0;" role="status" aria-live="polite">{{ session('success') }}</p>
                        @endif
                        @if($errors->any())
                            <p style="color:#ff8f8f;" role="alert">{{ $errors->first() }}</p>
                        @endif
                        <form action="{{ route('lead.store') }}" method="POST" data-submit-label="Đang gửi...">
                            @csrf
                            <input type="hidden" name="source_page" value="course_detail">
                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                            <div class="form-clt">
                                <input type="text" name="name" placeholder="H&#7885; v&#224; t&#234;n (*)" value="{{ old('name') }}" autocomplete="name" aria-label="Họ và tên" required>
                            </div>
                            <div class="form-clt">
                                <input type="tel" name="phone" placeholder="S&#7889; &#273;i&#7879;n tho&#7841;i (*)" value="{{ old('phone') }}" inputmode="tel" autocomplete="tel" aria-label="Số điện thoại" required>
                            </div>
                            <div class="form-clt">
                                <input type="email" name="email" placeholder="Email (kh&#244;ng b&#7855;t bu&#7897;c)" value="{{ old('email') }}" autocomplete="email" aria-label="Email">
                            </div>
                            <div class="form-clt">
                                <textarea name="message" placeholder="Nhu c&#7847;u c&#7911;a b&#7841;n (kh&#244;ng b&#7855;t bu&#7897;c)" aria-label="Nhu cầu tư vấn">{{ old('message') }}</textarea>
                            </div>
                            <div class="hp-wrap" aria-hidden="true">
                                <label for="hp-website-home">Website</label>
                                <input type="text" name="website" id="hp-website-home" tabindex="-1" autocomplete="off">
                            </div>
                            <button type="submit" class="theme-btn">&#272;&#259;ng k&#253; t&#432; v&#7845;n <i class="fa-solid fa-arrow-up-right"></i></button>
                            <p class="lead-note">Ch&#250;ng t&#244;i s&#7869; li&#234;n h&#7879; s&#7899;m &#273;&#7875; t&#432; v&#7845;n l&#7897; tr&#236;nh ph&#249; h&#7907;p.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
