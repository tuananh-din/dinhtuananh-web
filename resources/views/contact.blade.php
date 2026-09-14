@extends('layouts.master')
@section('page_title', 'Liên hệ | ' . data_get($infor, 'name', 'Personal Brand'))
@section('og_title', 'Liên hệ | ' . data_get($infor, 'name', 'Personal Brand'))
@section('content')
<section class="contact-shell fix">
    <div class="container">
        <div class="contact-card contact-card--enhanced">
            <div class="contact-info-panel">
                <h3>Trao đổi cùng Tuấn Anh</h3>
                <p>Để lại thông tin, tôi sẽ tư vấn hướng đi phù hợp với mục tiêu của bạn.</p>
                @if(data_get($contact, 'tel'))<a href="tel:{{ data_get($contact, 'tel') }}"><i class="fa-solid fa-phone"></i> {{ data_get($contact, 'tel') }}</a>@endif
                @if(data_get($contact, 'email'))<a href="mailto:{{ data_get($contact, 'email') }}"><i class="fa-solid fa-envelope"></i> {{ data_get($contact, 'email') }}</a>@endif
                <div class="contact-social-links">
                    @foreach(['facebook' => 'Facebook', 'instagram' => 'Instagram', 'linkedin' => 'LinkedIn', 'x' => 'X'] as $key => $label)
                        @if(data_get($contact, $key))<a href="{{ data_get($contact, $key) }}" target="_blank" rel="noopener">{{ $label }}</a>@endif
                    @endforeach
                </div>
                <p class="contact-note">Phản hồi trong giờ làm việc.</p>
            </div>
            <div class="contact-form-panel">
            <div class="section-title">
                <h6>Li&#234;n h&#7879;</h6>
                <h2>&#272;&#259;ng k&#253; t&#432; v&#7845;n nhanh</h2>
            </div>

            @php($leadErrors = $errors->getBag('lead'))
            @if(data_get(session('notice'), 'context') === 'lead')
                @include('partials.notice-banner', array_merge(session('notice'), ['dismissible' => true]))
            @endif
            @if($leadErrors->any())
                @include('partials.notice-banner', ['type' => 'error', 'title' => 'Vui lòng kiểm tra lại thông tin', 'messages' => $leadErrors->all()])
            @endif

            <form action="{{ route('lead.store') }}" method="POST" class="mt-4" data-submit-label="Đang gửi...">
                @csrf
                {{-- Honeypot chống bot: bot điền, user thật không thấy. --}}
                <div class="hp-wrap" aria-hidden="true">
                    <label for="hp-website-contact">Website</label>
                    <input type="text" name="website" id="hp-website-contact" tabindex="-1" autocomplete="off">
                </div>
                <input type="hidden" name="source_page" value="contact_page">
                <div class="form-clt">
                    <label for="contact-name">Họ và tên <span aria-hidden="true">*</span></label>
                    <input id="contact-name" type="text" name="name" placeholder="Ví dụ: Nguyễn Minh Anh" value="{{ old('name') }}" autocomplete="name" required>
                </div>
                <div class="form-clt">
                    <label for="contact-phone">Số điện thoại <span aria-hidden="true">*</span></label>
                    <input id="contact-phone" type="tel" name="phone" placeholder="Ví dụ: 0901 234 567" value="{{ old('phone') }}" inputmode="tel" autocomplete="tel" pattern="[0-9+() -]{8,30}" aria-describedby="contact-phone-hint" required>
                    <p id="contact-phone-hint" class="form-field-hint">Nhập số để nhận tư vấn nhanh.</p>
                </div>
                <div class="form-clt">
                    <label for="contact-email">Email <span class="lead-form-field__optional">Không bắt buộc</span></label>
                    <input id="contact-email" type="email" name="email" placeholder="ten@domain.com" value="{{ old('email') }}" autocomplete="email">
                </div>
                @php($selectedCourseId = old('course_id', $selectedCourse?->id))
                <div class="form-clt">
                    <label for="course_id">Khóa học quan tâm <span class="lead-form-field__optional">Không bắt buộc</span></label>
                    <select name="course_id" id="course_id">
                        <option value="">Kh&#243;a h&#7885;c quan t&#226;m (kh&#244;ng b&#7855;t bu&#7897;c)</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" @selected((string) $selectedCourseId === (string) $course->id)>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-clt">
                    <label for="contact-message">Mục tiêu hoặc câu hỏi <span class="lead-form-field__optional">Không bắt buộc</span></label>
                    <textarea id="contact-message" name="message" placeholder="Ví dụ: Tôi muốn tư vấn lộ trình học phù hợp.">{{ old('message') }}</textarea>
                </div>
                <button type="submit" class="theme-btn">&#272;&#259;ng k&#253; t&#432; v&#7845;n <i class="fa-solid fa-arrow-up-right"></i></button>
            </form>

            </div>
        </div>
    </div>
</section>
@endsection
