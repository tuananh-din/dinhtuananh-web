@extends('layouts.master')

@section('page_title', 'Không tìm thấy trang | ' . data_get($infor, 'name', 'Personal Brand'))

@section('content')
    <section class="error-page">
        <div class="container">
            <div class="error-page__inner">
                <span class="error-page__code">404</span>
                <h1>Không tìm thấy trang</h1>
                <p>Trang bạn tìm không tồn tại hoặc đã được di chuyển.</p>
                <div class="error-page__actions">
                    <a href="{{ route('index') }}" class="theme-btn">Về Trang chủ</a>
                    <a href="{{ route('blogs') }}" class="theme-btn">Xem Blog</a>
                    <a href="{{ route('courses') }}" class="theme-btn">Xem Khóa học</a>
                </div>
            </div>
        </div>
    </section>
@endsection
