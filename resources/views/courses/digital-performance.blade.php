@extends('layouts.master')
@section('body_class', 'is-dpm')
@php
    $instructorName = data_get($contact, 'name') ?: 'Đinh Tuấn Anh';
    $instructorAvatar = data_get($contact, 'avatar');
    if ($instructorAvatar && !\Illuminate\Support\Str::startsWith($instructorAvatar, ['http://', 'https://', '//', '/'])) {
        $instructorAvatar = asset($instructorAvatar);
    }
    $pageDescription = $course->seo_description ?: $course->short_description;
@endphp
@section('page_title', $course->seo_title ?: $course->title . ' | ' . $instructorName)
@section('meta_description', $pageDescription)
@section('og_title', $course->title)
@section('og_description', $pageDescription)
@if($course->thumbnail)
    @section('og_image', url($course->thumbnail))
@endif
@push('head')
<link rel="stylesheet" href="{{ asset('site/assets/css/digital-performance.css') }}?v={{ filemtime(public_path('site/assets/css/digital-performance.css')) }}">
@endpush
@push('structured_data')
@include('partials.jsonld-course', ['course' => $course])
@endpush
@section('content')

<div class="dpm-page">
@if(!empty($isPreview))<div class="dpm-preview" role="status">Bản xem trước — khóa học chưa mở</div>@endif
<section class="dpm-hero" aria-labelledby="dpm-title">
 <div class="dpm-wrap dpm-hero-grid">
  <div>
   <p class="dpm-eyebrow"><span></span> ĐINH TUẤN ANH / KHÓA HỌC DIGITAL MARKETING</p>
   <h1 id="dpm-title">{{ $course->title }}</h1>
   <p class="dpm-hero-message">Hiểu dữ liệu.<br><em>Làm chủ chiến dịch.</em></p>
   <p class="dpm-lead">{{ $course->short_description }}</p>
   <div class="dpm-actions"><a class="dpm-button" href="#dang-ky">Đăng ký tư vấn <span aria-hidden="true">↗</span></a><a class="dpm-text-link" href="#lo-trinh">Khám phá lộ trình ↓</a></div>
   <p class="dpm-hero-note">Video bài giảng · Thực hành · Nhóm hỏi đáp, chữa bài</p>
  </div>
  <div class="dpm-hero-media">
   <figure class="dpm-hero-visual"><img src="{{ asset('site/assets/img/courses/digital-performance-workspace.png') }}" width="1536" height="1024" fetchpriority="high" alt="Không gian làm việc với laptop hiển thị biểu đồ phân tích Marketing màu lime trên nền đen"><figcaption><span>ĐỌC DỮ LIỆU · HIỂU VẤN ĐỀ · CHỌN CÁCH LÀM</span></figcaption></figure>
   <div class="dpm-hero-facts" aria-label="Hình thức khóa học"><div><strong>09 buổi</strong><span>Lộ trình học</span></div><div><strong>Video</strong><span>Học theo từng bài</span></div><div><strong>Chữa bài</strong><span>Trao đổi trong nhóm</span></div></div>
  </div>
 </div>
</section>
<nav class="dpm-subnav" aria-label="Nội dung khóa học"><div class="dpm-wrap"><a href="#tong-quan">Tổng quan</a><a href="#lo-trinh">Lộ trình</a><a href="#cach-hoc">Cách học</a><a href="#giang-vien">Giảng viên</a><a href="#hoc-phi">Học phí</a><a href="#dang-ky">Đăng ký ↗</a></div></nav>
<section class="dpm-section" id="tong-quan">
 <div class="dpm-wrap">
  <div class="dpm-section-head"><div><p class="dpm-eyebrow">01 / BẮT ĐẦU TỪ ĐÚNG NỀN TẢNG</p><h2>Những ai nên tham gia<br>khóa học này?</h2></div><p>Dành cho bạn muốn xây dựng nền tảng, phát triển nghề nghiệp hoặc tìm hướng đi phù hợp trong Digital Marketing.</p></div>
  <div class="dpm-three">
   <article class="dpm-audience"><span class="dpm-number">01</span><h3>Sinh viên định hướng Marketing</h3><p>Bạn muốn trau dồi kiến thức, thực hành kỹ năng Marketing và chuẩn bị tốt hơn cho cơ hội nghề nghiệp sau khi ra trường.</p></article>
   <article class="dpm-audience"><span class="dpm-number">02</span><h3>Marketer muốn phát triển</h3><p>Bạn muốn nâng cao chuyên môn, hệ thống hóa cách làm và phát triển bền vững hơn trong lĩnh vực Digital Marketing.</p></article>
   <article class="dpm-audience"><span class="dpm-number">03</span><h3>Người mới, kinh doanh & chuyển ngành</h3><p>Bạn đang khởi nghiệp, kinh doanh online hoặc chuyển ngành; cần một khóa tổng quan để hiểu Digital Marketing và tìm hướng đi phù hợp.</p></article>
  </div>
 </div>
</section>
<section class="dpm-section dpm-outcomes">
 <div class="dpm-wrap dpm-two">
  <div><p class="dpm-eyebrow">HỌC ĐỂ ÁP DỤNG</p><h2>Lập kế hoạch, chạy quảng cáo<br>và <em>đánh giá hiệu quả.</em></h2><p class="dpm-muted">Các kỹ năng bạn sẽ được hướng dẫn và thực hành xuyên suốt khóa học.</p></div>
  <div class="dpm-outcome-list">
   <article><span>↗</span><div><h3>Xác định mục tiêu & chỉ số</h3><p>Kết nối mục tiêu kinh doanh với hành trình khách hàng và chỉ số đo lường phù hợp.</p></div></article>
   <article><span>↗</span><div><h3>Lập kế hoạch đa kênh</h3><p>Lựa chọn kênh, định hướng nội dung và phân bổ ngân sách theo mục tiêu chiến dịch.</p></div></article>
   <article><span>↗</span><div><h3>Triển khai & thử nghiệm</h3><p>Thực hành thiết lập quảng cáo, rà soát lỗi và xây dựng giả thuyết tối ưu.</p></div></article>
   <article><span>↗</span><div><h3>Đọc báo cáo, đề xuất hành động</h3><p>Nhận diện điểm nghẽn và trình bày những bước cải thiện tiếp theo từ dữ liệu.</p></div></article>
  </div>
 </div>
</section>
@include('partials.dpm-analytics')
<section class="dpm-section" id="lo-trinh">
 <div class="dpm-wrap">
  <div class="dpm-section-head"><div><p class="dpm-eyebrow">02 / NỘI DUNG KHÓA HỌC</p><h2>9 buổi học.<br>Từ tư duy đến thực hành.</h2></div><p>Tổng quan → Kế hoạch → Nội dung → Kênh → Quảng cáo → Thực hành → Tối ưu → Phân tích. Mở từng buổi để xem chi tiết.</p></div>
  <div class="dpm-curriculum">@foreach(config('digital-performance.modules', []) as $module)
<details @if($loop->first) open @endif><summary><span class="dpm-module-number">Buổi {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><span class="dpm-module-title">{{ $module[0] }}</span><span class="dpm-expand" aria-hidden="true"></span></summary><p>{{ $module[1] }}</p></details>
@endforeach</div>
  @if($course->content)<div class="dpm-extra">{!! $course->content !!}</div>@endif
  <div class="dpm-curriculum-footer"><p>Tài liệu đi kèm khóa học · Nhóm hỏi đáp và chữa bài thực hành.</p><a class="dpm-text-link" href="#dang-ky">Nhận tư vấn lộ trình ↗</a></div>
 </div>
</section>
<section class="dpm-section dpm-learning" id="cach-hoc">
 <div class="dpm-wrap"><p class="dpm-eyebrow">03 / HỌC THEO NHỊP CỦA BẠN</p><h2>Học qua video.<br>Hỏi đáp và chữa bài trong nhóm.</h2>
  <div class="dpm-three">
   <article><span class="dpm-number">01 — XEM & HIỂU</span><h3>Video do giảng viên hướng dẫn</h3><p>Theo dõi nội dung theo lộ trình và quay lại phần cần củng cố trong thời hạn truy cập được xác nhận khi đăng ký.</p></article>
   <article><span class="dpm-number">02 — LÀM & KIỂM CHỨNG</span><h3>Thực hành theo chủ đề</h3><p>Chuyển kiến thức thành kế hoạch, cách thiết lập và phân tích kết quả qua các bài thực hành.</p></article>
   <article><span class="dpm-number">03 — HỎI & CẢI THIỆN</span><h3>Nhóm hỏi đáp, chữa bài</h3><p>Trao đổi các phần chưa rõ, gửi bài thực hành và nhận góp ý để điều chỉnh cách làm.</p></article>
  </div>
 </div>
</section>
<section class="dpm-section" id="giang-vien">
 <div class="dpm-wrap dpm-instructor">
  <div class="dpm-instructor-profile">
   <div class="dpm-portrait">@if($instructorAvatar)<img src="{{ $instructorAvatar }}" alt="{{ $instructorName }}" loading="lazy" width="600" height="700">@else<div class="dpm-monogram" aria-hidden="true">TA<span>Đinh Tuấn Anh</span></div>@endif<span>GIẢNG VIÊN ĐỒNG HÀNH</span></div>
   @if(data_get($contact, 'description'))<p class="dpm-profile-summary">{{ data_get($contact, 'description') }}</p>@endif
  </div>
  <div><p class="dpm-eyebrow">04 / HỌC CÙNG</p><h2>{{ $instructorName }}</h2><p class="dpm-instructor-intro">Trực tiếp giảng dạy khóa Digital Performance Management.</p>
@if(data_get($contact, 'about_me'))<div class="dpm-bio">{!! data_get($contact, 'about_me') !!}</div>@endif<div class="dpm-instructor-note">Bạn có thể trao đổi trực tiếp với giảng viên trong nhóm để giải đáp thắc mắc và nhận góp ý cho bài làm.</div><a class="dpm-text-link" href="{{ route('about') }}">Tìm hiểu thêm về giảng viên ↗</a></div>
 </div>
</section>
<section class="dpm-section dpm-enroll" id="hoc-phi">
 <div class="dpm-wrap dpm-two">
  <div><p class="dpm-eyebrow">05 / ĐẦU TƯ CHO KIẾN THỨC</p><h2>Đăng ký khóa học<br>Digital Performance.</h2><p class="dpm-price">{{ number_format($course->sale_price ?? $course->price ?? 3000000, 0, ',', '.') }} <span>VNĐ</span></p><p class="dpm-price-label">Học phí khóa học</p>
   <ul class="dpm-benefits"><li>Lộ trình từ nền tảng đến thực hành</li><li>Video bài giảng do Đinh Tuấn Anh hướng dẫn</li><li>Nhóm hỏi đáp và chữa bài</li><li>Tài liệu đi kèm khóa học</li><li>Thực hành lập kế hoạch, triển khai và phân tích</li></ul>
   <p class="dpm-small">Thông tin truy cập và hướng dẫn tham gia nhóm được xác nhận trong quá trình đăng ký.</p>
  </div>
  <div class="dpm-form-card" id="dang-ky"><p class="dpm-eyebrow">TRAO ĐỔI VỀ KHÓA HỌC</p><h3>Đăng ký tư vấn</h3><p>Để lại thông tin để được tư vấn nội dung, cách học và hướng dẫn đăng ký.</p>
@php($leadErrors = $errors->getBag('lead'))
@if(data_get(session('notice'), 'context') === 'lead')
@include('partials.notice-banner', array_merge(session('notice'), ['dismissible' => true]))
@endif
@if($leadErrors->any())
@include('partials.notice-banner', ['type' => 'error', 'title' => 'Vui lòng kiểm tra lại thông tin', 'messages' => $leadErrors->all()])
@endif
<form action="{{ route('lead.store') }}" method="POST" data-submit-label="Đang gửi...">
@csrf
<input type="hidden" name="source_page" value="digital_performance_landing">
<input type="hidden" name="course_id" value="{{ $course->id }}">
<label for="dpm-name">Họ và tên <span>*</span></label><input id="dpm-name" name="name" value="{{ old('name') }}" required maxlength="255" autocomplete="name" placeholder="Tên của bạn">
<label for="dpm-phone">Số điện thoại <span>*</span></label><input id="dpm-phone" type="tel" name="phone" value="{{ old('phone') }}" required maxlength="30" autocomplete="tel" placeholder="Số điện thoại liên hệ">
<label for="dpm-email">Email <small>(không bắt buộc)</small></label><input id="dpm-email" type="email" name="email" value="{{ old('email') }}" maxlength="255" autocomplete="email" placeholder="Email của bạn">
<label for="dpm-message">Bạn muốn học để làm gì?</label><textarea id="dpm-message" name="message" rows="3" placeholder="Chia sẻ mục tiêu hoặc điều bạn muốn hỏi...">{{ old('message') }}</textarea>
<div class="dpm-honeypot" aria-hidden="true"><label for="dpm-website">Website</label><input id="dpm-website" name="website" tabindex="-1" autocomplete="off"></div>
<button class="dpm-button" type="submit">Gửi đăng ký tư vấn <span aria-hidden="true">↗</span></button><p class="dpm-small">Thông tin được sử dụng để liên hệ tư vấn khóa học cho bạn.</p>
</form></div>
 </div>
</section>
<section class="dpm-section dpm-faq"><div class="dpm-wrap dpm-two"><div><p class="dpm-eyebrow">GIẢI ĐÁP TRƯỚC KHI HỌC</p><h2>Câu hỏi thường gặp</h2></div><div>
 <details><summary>Chưa từng chạy quảng cáo có học được không?</summary><p>Khóa học bắt đầu từ kiến thức nền tảng. Nếu chưa có kinh nghiệm, bạn có thể trao đổi trước để được tư vấn mức độ phù hợp và cách chuẩn bị.</p></details>
 <details><summary>Khóa học được tổ chức như thế nào?</summary><p>Bạn học qua video do Đinh Tuấn Anh giảng dạy, thực hành theo nội dung và tham gia nhóm hỏi đáp, chữa bài.</p></details>
 <details><summary>Gặp khó khăn khi làm bài thì hỏi ở đâu?</summary><p>Bạn có thể gửi câu hỏi và bài thực hành trong nhóm học tập để được hướng dẫn và góp ý.</p></details>
 <details><summary>Học phí có bao gồm ngân sách chạy quảng cáo?</summary><p>Học phí khóa học là {{ number_format($course->sale_price ?? $course->price ?? 3000000, 0, ',', '.') }} VNĐ. Nếu muốn chạy chiến dịch thật, hãy trao đổi trước về ngân sách quảng cáo và các chi phí công cụ phát sinh.</p></details>
 <details><summary>Được xem video trong bao lâu?</summary><p>Thời hạn truy cập sẽ được xác nhận khi tư vấn, trước khi bạn quyết định đăng ký.</p></details>
 </div></div></section>
<div class="dpm-closing"><div class="dpm-wrap"><span>Học có hệ thống. Thực hành có định hướng.</span><a href="#dang-ky">Bắt đầu cùng Tuấn Anh ↗</a></div></div>
</div>

@endsection
