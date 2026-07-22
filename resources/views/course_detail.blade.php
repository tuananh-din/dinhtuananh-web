@extends('layouts.master')
@section('content')
<style>
    .course-detail-shell {
        padding: 110px 0;
    }

    .course-main-card,
    .course-side-card {
        background: #121212;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 22px;
        padding: 28px;
    }

    .course-meta-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .course-meta-list li {
        padding: 10px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .course-price-box {
        margin-bottom: 18px;
    }

    .course-price-box .old {
        text-decoration: line-through;
        color: #9a9a9a;
        margin-right: 8px;
    }

    .cta-stack {
        display: grid;
        gap: 12px;
        margin-top: 22px;
    }

    .lead-form-box {
        margin-top: 18px;
        padding-top: 18px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .lead-form-box .form-clt {
        margin-bottom: 10px;
    }

    .lead-form-box input,
    .lead-form-box textarea {
        width: 100%;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: #0e0e0e;
        color: #fff;
        border-radius: 10px;
        padding: 10px 12px;
    }

    .lead-form-box textarea {
        min-height: 90px;
        resize: vertical;
    }

    .lead-form-box .lead-note {
        font-size: 13px;
        color: #bdbdbd;
        margin-top: 8px;
    }
</style>

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
                        <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" style="max-width:100%;height:auto;border-radius:16px;">
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
                        <li><strong>N&#7873;n t&#7843;ng:</strong> {{ $course->platform ?: '&#272;a n&#7873;n t&#7843;ng' }}</li>
                        <li><strong>C&#7845;p &#273;&#7897;:</strong> {{ $course->level ?: 'Ph&#249; h&#7907;p ng&#432;&#7901;i m&#7899;i' }}</li>
                        <li><strong>Th&#7901;i l&#432;&#7907;ng:</strong> {{ $course->duration_text ?: 'Theo l&#7897; tr&#236;nh th&#7921;c t&#7871;' }}</li>
                        <li><strong>H&#236;nh th&#7913;c:</strong> {{ $course->format ?: 'Online / t&#432; v&#7845;n tr&#7921;c ti&#7871;p' }}</li>
                    </ul>

                    <div class="cta-stack">
                        @if($course->cta_link)
                        <a href="{{ $course->cta_link }}" class="theme-btn">
                            {{ $course->cta_text ?: '&#272;&#259;ng k&#253; h&#7885;c ngay' }}
                            <i class="fa-solid fa-arrow-up-right"></i>
                        </a>
                        @else
                        <a href="{{ route('contact') }}" class="theme-btn">
                            Li&#234;n h&#7879; t&#432; v&#7845;n
                            <i class="fa-solid fa-arrow-up-right"></i>
                        </a>
                        @endif
                        <a href="{{ route('courses') }}" class="theme-btn border-btn">
                            Xem kh&#243;a h&#7885;c kh&#225;c
                            <i class="fa-solid fa-arrow-up-right"></i>
                        </a>
                    </div>

                    <div class="lead-form-box">
                        <h4>&#272;&#7875; l&#7841;i th&#244;ng tin t&#432; v&#7845;n</h4>
                        @if(session('success'))
                            <p style="color:#7af2a0;">{{ session('success') }}</p>
                        @endif
                        @if($errors->any())
                            <p style="color:#ff8f8f;">{{ $errors->first() }}</p>
                        @endif
                        <form action="{{ route('lead.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="source_page" value="course_detail">
                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                            <div class="form-clt">
                                <input type="text" name="name" placeholder="H&#7885; v&#224; t&#234;n (*)" value="{{ old('name') }}" required>
                            </div>
                            <div class="form-clt">
                                <input type="text" name="phone" placeholder="S&#7889; &#273;i&#7879;n tho&#7841;i (*)" value="{{ old('phone') }}" required>
                            </div>
                            <div class="form-clt">
                                <input type="email" name="email" placeholder="Email (kh&#244;ng b&#7855;t bu&#7897;c)" value="{{ old('email') }}">
                            </div>
                            <div class="form-clt">
                                <textarea name="message" placeholder="Nhu c&#7847;u c&#7911;a b&#7841;n (kh&#244;ng b&#7855;t bu&#7897;c)">{{ old('message') }}</textarea>
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
