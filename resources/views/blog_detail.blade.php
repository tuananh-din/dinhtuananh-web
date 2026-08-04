@extends('layouts.master')
@php
    // A-4: chuẩn hoá SEO/OG cho từng bài. Ưu tiên description ngắn, fallback từ content (155 ký tự).
    $blogDescription = \Illuminate\Support\Str::limit(
        strip_tags($blog->description ?: ($blog->content ?? '')),
        155
    );
    $blogBrand = data_get($infor, 'name', 'Personal Brand');
@endphp
@section('page_title', $blog->title . ' | ' . $blogBrand)
@section('meta_description', $blogDescription)
@section('og_title', $blog->title)
@section('og_description', $blogDescription)
@section('og_type', 'article')
@if(!empty($blog->image))
    @section('og_image', \Illuminate\Support\Str::startsWith($blog->image, ['http://','https://','//']) ? $blog->image : asset(ltrim($blog->image, '/')))
@endif
@push('structured_data')
    @include('partials.jsonld-article', ['blog' => $blog])
    @include('partials.jsonld-breadcrumbs', ['items' => [
        ['name' => 'Trang chủ', 'url' => route('index')],
        ['name' => 'Blog', 'url' => route('blogs')],
        ['name' => $blog->title, 'url' => url()->current()],
    ]])
@endpush
@section('content')
<section class="news-grid-section1 fix">
    <div class="container">
        <h1>{{ $blog->title }}</h1>
        <span class="post-date">{{ optional($blog->created_at)->format('d/m/Y') }}</span>
        @if($blog->description)
        <p class="mb-4">{{ $blog->description }}</p>
        @endif
        <div class="blog-share">
            <span>Chia sẻ:</span>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($blog->title) }}" target="_blank" rel="noopener" aria-label="X (Twitter)"><i class="fab fa-x-twitter"></i></a>
            <button type="button" class="btn-copy-link" data-copy-url="{{ url()->current() }}" aria-label="Copy link"><i class="fa-solid fa-link"></i></button>
        </div>
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="news-details-area">
                    <div class="single-news-post">
                        <div class="news-content">
                            {!! $blog->content !!}
                        </div>
                    </div>
                </div>
                <div class="other-blogs mt-5">
                    <h3>Nhận bài viết mới qua email</h3>
                    <p>Đăng ký để không bỏ lỡ kiến thức marketing và quảng cáo thực chiến.</p>
                    <form action="{{ route('newsletter.store') }}" method="POST" class="d-flex flex-wrap gap-2">
                        @csrf
                        <input type="hidden" name="source" value="blog_detail">
                        <input type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true" style="display:none">
                        <label class="visually-hidden" for="blog-newsletter-email">Email</label>
                        <input id="blog-newsletter-email" type="email" name="email" required class="form-control" placeholder="Email của bạn">
                        <button type="submit" class="theme-btn">Đăng ký</button>
                    </form>
                </div>
                @if(!empty($otherBlogs) && $otherBlogs->isNotEmpty())
                <div class="other-blogs mt-5">
                    <h3>Bài viết khác</h3>
                    <div class="row g-4 mt-2">
                        @foreach($otherBlogs as $other)
                        <div class="col-lg-4 col-md-6">
                            <div class="blog-mini-card">
                                <img src="{{ $other->image_url }}" alt="{{ $other->title }}">
                                <div class="content">
                                    <span class="post-date">{{ optional($other->created_at)->format('d/m/Y') }}</span>
                                    <h3><a href="{{ route('blog', $other->slug) }}">{{ $other->title }}</a></h3>
                                    @if($other->description)<p>{{ $other->description }}</p>@endif
                                    <a href="{{ route('blog', $other->slug) }}">Đọc bài viết <i class="fa-solid fa-arrow-up-right"></i></a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-copy-link');
    if (!btn) return;
    const url = btn.dataset.copyUrl;
    if (!url) return;
    const done = () => { if (window.toastr) toastr.info('Đã copy link'); else alert('Đã copy link'); };
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(done).catch(() => fallback());
    } else {
        fallback();
    }
    function fallback() {
        const ta = document.createElement('textarea');
        ta.value = url; document.body.appendChild(ta); ta.select();
        try { document.execCommand('copy'); done(); } catch (_) {}
        document.body.removeChild(ta);
    }
});
</script>
@endpush
