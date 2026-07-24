@extends('layouts.master')
@section('page_title', 'Liên hệ | ' . data_get($infor, 'name', 'Personal Brand'))
@section('og_title', 'Liên hệ | ' . data_get($infor, 'name', 'Personal Brand'))
@section('content')
<section class="contact-shell fix">
    <div class="container">
        <div class="contact-card">
            <div class="section-title">
                <h6>Li&#234;n h&#7879;</h6>
                <h2>&#272;&#259;ng k&#253; t&#432; v&#7845;n nhanh</h2>
            </div>

            @if(session('success'))
                <p style="color:#7af2a0;">{{ session('success') }}</p>
            @endif
            @if($errors->any())
                <p style="color:#ff8f8f;">{{ $errors->first() }}</p>
            @endif

            <form action="{{ route('lead.store') }}" method="POST" class="mt-4">
                @csrf
                {{-- Honeypot chống bot: bot điền, user thật không thấy. --}}
                <div class="hp-wrap" aria-hidden="true">
                    <label for="hp-website-contact">Website</label>
                    <input type="text" name="website" id="hp-website-contact" tabindex="-1" autocomplete="off">
                </div>
                <input type="hidden" name="source_page" value="contact_page">
                <div class="form-clt">
                    <input type="text" name="name" placeholder="H&#7885; v&#224; t&#234;n (*)" value="{{ old('name') }}" autocomplete="name" aria-label="Họ và tên" required>
                </div>
                <div class="form-clt">
                    <input type="tel" name="phone" placeholder="S&#7889; &#273;i&#7879;n tho&#7841;i (*)" value="{{ old('phone') }}" inputmode="tel" autocomplete="tel" aria-label="Số điện thoại" required>
                </div>
                <div class="form-clt">
                    <input type="email" name="email" placeholder="Email (kh&#244;ng b&#7855;t bu&#7897;c)" value="{{ old('email') }}" autocomplete="email" aria-label="Email">
                </div>
                @php($selectedCourseId = old('course_id', $selectedCourse?->id))
                <div class="form-clt">
                    <select name="course_id" id="course_id" aria-label="Khóa học quan tâm">
                        <option value="">Kh&#243;a h&#7885;c quan t&#226;m (kh&#244;ng b&#7855;t bu&#7897;c)</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" @selected((string) $selectedCourseId === (string) $course->id)>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-clt">
                    <textarea name="message" placeholder="Nhu c&#7847;u c&#7911;a b&#7841;n (kh&#244;ng b&#7855;t bu&#7897;c)" aria-label="Nhu cầu tư vấn">{{ old('message') }}</textarea>
                </div>
                <button type="submit" class="theme-btn">&#272;&#259;ng k&#253; t&#432; v&#7845;n <i class="fa-solid fa-arrow-up-right"></i></button>
            </form>

            <p class="contact-note">Ch&#250;ng t&#244;i s&#7869; li&#234;n h&#7879; l&#7841;i trong gi&#7901; l&#224;m vi&#7879;c.</p>
        </div>
    </div>
</section>
@endsection
