@extends('layouts.master')
@php
    // A-4: chuẩn hoá SEO/OG cho từng bài. Ưu tiên description ngắn, fallback từ content (155 ký tự).
    $blogDescription = \Illuminate\Support\Str::limit(
        strip_tags($blog->description ?: ($blog->content ?? '')),
        155
    );
    $blogBrand = data_get($infor, 'name', 'Personal Brand');
    $blogWordCount = count(preg_split('/\s+/u', trim(strip_tags($blog->content ?? '')), -1, PREG_SPLIT_NO_EMPTY));
    $blogReadingMinutes = max(1, (int) ceil($blogWordCount / 200));
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
        <div class="blog-meta">
            <span class="post-date">{{ optional($blog->created_at)->format('d/m/Y') }}</span>
            <span class="post-date"><i class="fa-regular fa-clock" aria-hidden="true"></i> {{ $blogReadingMinutes }} phút đọc</span>
        </div>
        @if($blog->categories->isNotEmpty())
        <div class="blog-category-list" aria-label="Chuyên mục">
            @foreach($blog->categories as $category)
            <a class="blog-category-chip" href="{{ route('blogs', ['category' => $category->slug]) }}">{{ $category->name }}</a>
            @endforeach
        </div>
        @endif
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
                        <nav id="blog-toc" class="blog-toc d-none" aria-label="Mục lục bài viết">
                            <strong>Mục lục</strong>
                            <ol></ol>
                        </nav>
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
                                <img src="{{ $other->image_url }}" alt="{{ $other->title }}" loading="lazy">
                                <div class="content">
                                    <span class="post-date">{{ optional($other->created_at)->format('d/m/Y') }}</span>
                                    @if($other->categories->isNotEmpty())
                                    <div class="blog-category-list" aria-label="Chuyên mục">
                                        @foreach($other->categories as $category)
                                        <a class="blog-category-chip" href="{{ route('blogs', ['category' => $category->slug]) }}">{{ $category->name }}</a>
                                        @endforeach
                                    </div>
                                    @endif
                                    <h3><a href="{{ route('blog', $other->slug) }}">{{ $other->title }}</a></h3>
                                    @if($other->description)<p>{{ \Illuminate\Support\Str::limit($other->description, 160) }}</p>@endif
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

document.addEventListener('DOMContentLoaded', function () {
    const content = document.querySelector('.news-content');
    const toc = document.getElementById('blog-toc');
    if (!content || !toc) return;

    const headings = Array.from(content.querySelectorAll('h2, h3'));
    if (headings.length < 3) return;

    const list = toc.querySelector('ol');
    headings.forEach(function (heading, index) {
        if (!heading.id) heading.id = 'blog-heading-' + (index + 1);
        const item = document.createElement('li');
        item.className = heading.tagName.toLowerCase() === 'h3' ? 'blog-toc__subitem' : '';
        const link = document.createElement('a');
        link.href = '#' + heading.id;
        link.textContent = heading.textContent;
        item.appendChild(link);
        list.appendChild(item);
    });

    toc.classList.remove('d-none');
});
</script>
@endpush
