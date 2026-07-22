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
@if(!empty($blog->image))
    @section('og_image', \Illuminate\Support\Str::startsWith($blog->image, ['http://','https://','//']) ? $blog->image : asset(ltrim($blog->image, '/')))
@endif
@section('content')
<section class="news-grid-section1 fix">
    <style>
    .post-date { font-size: 0.85rem; color: #999; display: block; margin-bottom: 8px; }
    .blog-share { display: flex; align-items: center; gap: 10px; margin-bottom: 24px; }
    .blog-share a, .blog-share button { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; background: #f2f2f2; color: #333; text-decoration: none; border: none; cursor: pointer; }
    .blog-share a:hover, .blog-share button:hover { background: #e0e0e0; }
    .other-blogs h3 { margin-bottom: 8px; }
    </style>
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
                @if(!empty($otherBlogs) && $otherBlogs->isNotEmpty())
                <div class="other-blogs mt-5">
                    <h3>Bài viết khác</h3>
                    <div class="row g-4 mt-2">
                        @foreach($otherBlogs as $other)
                        <div class="col-lg-4 col-md-6">
                            <div class="blog-mini-card">
                                <img src="{{ $other->image ?: 'app/assets/images/others/thumb-16.jpg' }}" alt="{{ $other->title }}">
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
