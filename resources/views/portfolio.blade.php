@extends('layouts.master')
@section('page_title', 'Case Study | ' . data_get($infor, 'name', 'Personal Brand'))
@section('og_title', 'Case Study | ' . data_get($infor, 'name', 'Personal Brand'))
@section('content')
<section class="case-study-section section-padding">
    <div class="container">
        <div class="case-study-intro text-center">
            <h1>Case Study</h1>
            <p>Nơi tổng hợp các dự án, kết quả và minh chứng thực tế.</p>
        </div>

        @if($lifes->isNotEmpty())
        <div class="row g-4 case-study-grid">
            @foreach($lifes as $row)
            <div class="col-xl-4 col-md-6">
                <article class="case-card h-100">
                    <img src="{{ $row->image ?: 'app/assets/images/others/thumb-16.jpg' }}" alt="{{ $row->title ?: 'Case study' }}" loading="lazy">
                    <div class="content">
                        <h2>{{ $row->title ?: 'Case study' }}</h2>
                        @if($row->description)<p>{{ $row->description }}</p>@endif
                    </div>
                </article>
            </div>
            @endforeach
        </div>
        @else
        <div class="case-study-empty text-center">
            <h2>Case study đang được cập nhật</h2>
            <p>Các kết quả thực tế sẽ sớm được bổ sung tại đây.</p>
        </div>
        @endif
    </div>
</section>

<section class="cta-section section-padding fix pt-0">
    <div class="container">
        <div class="cta-text-items text-center">
            <h2 class="text_invert-2">Muốn xem cách tôi biến kiến thức thành kết quả thực tế?</h2>
            <p>Hãy bắt đầu từ blog để hiểu cách tôi trình bày kiến thức, sau đó liên hệ để nhận tư vấn khóa học hoặc chiến lược phù hợp.</p>
            <div class="cta-btn">
                <a href="{{ route('blogs') }}" class="theme-btn">Xem blog <i class="fa-solid fa-arrow-up-right"></i></a>
                <a href="tel:{{ $about->tel }}" class="theme-btn border-btn">Nhận tư vấn <i class="fa-solid fa-arrow-up-right"></i></a>
            </div>
        </div>
    </div>
</section>
@endsection