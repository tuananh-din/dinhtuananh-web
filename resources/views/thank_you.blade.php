@extends('layouts.master')

@section('page_title', 'Cảm ơn bạn')
@section('content')
<section class="news-grid-section1 fix">
    <div class="container text-center">
        <h1>Cảm ơn bạn!</h1>
        <p class="mb-4">Thông tin của bạn đã được ghi nhận. Chúng tôi sẽ sớm liên hệ hoặc gửi nội dung phù hợp tới email của bạn.</p>
        <a href="{{ route('index') }}" class="theme-btn">Về trang chủ</a>
        <a href="{{ route('courses') }}" class="theme-btn ms-2">Xem khóa học</a>
    </div>
</section>
@endsection

@push('conversion')
{{-- Dán mã sự kiện chuyển đổi Pixel/GA cho riêng trang này tại đây. --}}
@endpush
