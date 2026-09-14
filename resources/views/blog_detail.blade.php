@extends('layouts.master')
@php
    $blogBrand = data_get($infor, 'name', 'Personal Brand');

    // SEO: uu tien gia tri Admin da nhap (title_seo / desc_seo) roi moi fallback.
    // Truoc day 2 field nay duoc luu nhung KHONG duoc view su dung.
    $blogDescription = trim((string) $blog->desc_seo) ?: \Illuminate\Support\Str::limit(
        strip_tags($blog->description ?: ($blog->content ?? '')),
        155
    );
    $blogSeoTitle = trim((string) $blog->title_seo) ?: ($blog->title . ' | ' . $blogBrand);

    // Reading time: derive tu content (200 tu/phut), khong can field trong DB.
    $blogWordCount = count(preg_split('/\s+/u', trim(strip_tags($blog->content ?? '')), -1, PREG_SPLIT_NO_EMPTY));
    $blogReadingMinutes = max(1, (int) ceil($blogWordCount / 200));

    // Bien trung gian: khi nao co cot `published_at` thi chi doi 1 dong nay.
    $blogPublishedAt = $blog->created_at;

    // Tac gia: dung lai du lieu san co (bang `about` qua view composer), khong them cot.
    $blogAuthorName = data_get($contact, 'name') ?: data_get($infor, 'name');
    $blogAuthorAvatarRaw = trim((string) data_get($contact, 'avatar'));
    $blogAuthorAvatar = $blogAuthorAvatarRaw
        ? (\Illuminate\Support\Str::startsWith($blogAuthorAvatarRaw, ['http://', 'https://', '//', '/'])
            ? $blogAuthorAvatarRaw
            : asset($blogAuthorAvatarRaw))
        : null;

    // Cover: chi render khi bai THAT SU co anh. Accessor image_url tra ve anh
    // placeholder khi rong -> khong dung accessor de quyet dinh hien/an.
    $blogHasCover = !empty($blog->image);
    $blogCover = $blogHasCover ? $blog->image_url : null;
@endphp
@section('body_class', 'is-article')
@section('page_title', $blogSeoTitle)
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
@if(!empty($isPreview) && $isPreview)
<div class="container"><div class="alert alert-warning mt-3" role="status">Bản xem trước — bài chưa đăng</div></div>
@endif
<article class="article-page">
    <div class="container">

        {{-- ============================ HERO ============================ --}}
        <header class="article-hero">
            <div class="article-shell article-shell--title">
                @include('partials.breadcrumbs', ['items' => [
                    ['name' => 'Trang chủ', 'url' => route('index')],
                    ['name' => 'Blog', 'url' => route('blogs')],
                    ['name' => $blog->title],
                ]])

                @if($blog->categories->isNotEmpty())
                <div class="article-hero__eyebrow" aria-label="Chuyên mục">
                    @foreach($blog->categories as $category)
                    <a href="{{ route('blogs', ['category' => $category->slug]) }}">{{ $category->name }}</a>
                    @endforeach
                </div>
                @endif

                <h1 class="article-hero__title">{{ $blog->title }}</h1>

                @if($blog->description)
                <p class="article-hero__subtitle">{{ $blog->description }}</p>
                @endif

                <div class="article-hero__meta">
                    @if($blogAuthorAvatar)
                    <img class="article-hero__avatar" src="{{ $blogAuthorAvatar }}" alt="" width="36" height="36" loading="lazy">
                    @endif
                    @if($blogAuthorName)
                    <span class="article-hero__author">{{ $blogAuthorName }}</span>
                    <span class="article-hero__dot" aria-hidden="true">·</span>
                    @endif
                    <time datetime="{{ optional($blogPublishedAt)->toDateString() }}">{{ optional($blogPublishedAt)->format('d/m/Y') }}</time>
                    <span class="article-hero__dot" aria-hidden="true">·</span>
                    <span>{{ $blogReadingMinutes }} phút đọc</span>
                </div>
            </div>

            @if($blogHasCover)
            {{-- Cover dung container aspect-ratio 16/9 + object-fit:cover.
                 Ly do: ImageOptimizer chi scaleDown theo chieu rong, KHONG dam bao
                 ti le anh, va DB khong luu dimension. Container co ti le co dinh =>
                 khong CLS; object-fit:cover => khong meo anh. Doi lai anh khong phai
                 16:9 se bi cat bot -> day la VISUAL CROP CO CHU DICH. --}}
            <figure class="article-hero__cover">
                <img src="{{ $blogCover }}" alt="{{ $blog->title }}" fetchpriority="high" decoding="async">
            </figure>
            @endif
        </header>

        {{-- ============================ BODY ============================ --}}
        <div class="article-layout">
            <div class="article-layout__content">
                <div class="news-content">
                    {!! $blog->content !!}
                </div>
            </div>

            <aside class="article-aside">
                <nav id="blog-toc" class="blog-toc d-none" aria-label="Mục lục bài viết">
                    <strong>Mục lục</strong>
                    <ul></ul>
                </nav>
            </aside>
        </div>

        {{-- =========================== FOOTER =========================== --}}
        <footer class="article-footer">
            <div class="article-shell">
                <div class="blog-share">
                    <span>Chia sẻ:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($blog->title) }}" target="_blank" rel="noopener" aria-label="X (Twitter)"><i class="fab fa-twitter"></i></a>
                    <button type="button" class="btn-copy-link" data-copy-url="{{ url()->current() }}" aria-label="Sao ch&#233;p li&#234;n k&#7871;t"><i class="fa-solid fa-link"></i></button>
                </div>
                @include('partials.notice-banner', ['type' => 'info', 'title' => 'Đã sao chép liên kết', 'message' => 'Bạn có thể dán đường dẫn này để chia sẻ bài viết.', 'dismissible' => true, 'id' => 'blog-copy-notice', 'hidden' => true])
                @include('partials.notice-banner', ['type' => 'error', 'title' => 'Không thể sao chép liên kết', 'message' => 'Vui lòng thử lại hoặc sao chép đường dẫn trên thanh địa chỉ.', 'id' => 'blog-copy-error', 'hidden' => true])

                <div class="other-blogs">
                    <h3>Nhận bài viết mới qua email</h3>
                    <p>Đăng ký để không bỏ lỡ kiến thức marketing và quảng cáo thực chiến.</p>
                    <form action="{{ route('newsletter.store') }}" method="POST" class="d-flex flex-wrap gap-2" data-submit-label="Đang gửi...">
                        @csrf
                        <input type="hidden" name="source" value="blog_detail">
                        <input type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true" style="display:none">
                        <label class="visually-hidden" for="blog-newsletter-email">Email</label>
                        @php($blogNewsletterErrors = $errors->getBag('newsletterBlog'))
                        @if(data_get(session('notice'), 'context') === 'newsletter-blog')
                            @include('partials.notice-banner', array_merge(session('notice'), ['dismissible' => true]))
                        @endif
                        @if($blogNewsletterErrors->any())
                            @include('partials.notice-banner', ['type' => 'error', 'title' => 'Vui lòng kiểm tra email', 'messages' => $blogNewsletterErrors->all()])
                        @endif
                        <input id="blog-newsletter-email" type="email" name="email" required class="form-control" autocomplete="email" inputmode="email" placeholder="Ví dụ: ten@domain.com">
                        <button type="submit" class="theme-btn">Đăng ký</button>
                    </form>
                </div>

                @if($featuredCourse)
                <div class="blog-course-promo mt-5">
                    <h3>Khóa học nổi bật</h3>
                    <h4>{{ $featuredCourse->title }}</h4>
                    @if($featuredCourse->short_description)<p>{{ $featuredCourse->short_description }}</p>@endif
                    @if(!is_null($featuredCourse->sale_price))
                        <strong>{{ number_format($featuredCourse->sale_price, 0, ',', '.') }} VND</strong>
                    @elseif(!is_null($featuredCourse->price))
                        <strong>{{ number_format($featuredCourse->price, 0, ',', '.') }} VND</strong>
                    @endif
                    <div class="mt-3"><a class="theme-btn" href="{{ route('course.detail', $featuredCourse->slug) }}">Xem khóa học</a></div>
                </div>
                @endif
            </div>

            @if(!empty($otherBlogs) && $otherBlogs->isNotEmpty())
            <div class="article-related article-shell article-shell--wide">
                <div class="other-blogs">
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
            </div>
            @endif
        </footer>

    </div>
</article>
@endsection
@push('scripts')
<script>
try {
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-copy-link');
    if (!btn) return;
    const url = btn.dataset.copyUrl;
    if (!url) return;
    const showNotice = (id) => {
        document.querySelectorAll('#blog-copy-notice, #blog-copy-error').forEach((notice) => {
            notice.hidden = true;
        });
        const notice = document.getElementById(id);
        if (notice) notice.hidden = false;
    };
    const done = () => showNotice('blog-copy-notice');
    const failed = () => showNotice('blog-copy-error');
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(done).catch(() => fallback());
    } else {
        fallback();
    }
    function fallback() {
        const ta = document.createElement('textarea');
        ta.value = url; document.body.appendChild(ta); ta.select();
        try {
            if (document.execCommand('copy')) done(); else failed();
        } catch (_) {
            failed();
        }
        document.body.removeChild(ta);
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const content = document.querySelector('.news-content');
    const toc = document.getElementById('blog-toc');
    const layout = document.querySelector('.article-layout');
    if (!content || !toc || !layout) return;

    const headings = Array.from(content.querySelectorAll('h2, h3'));
    if (headings.length < 3) {
        layout.classList.add('article-layout--no-toc');
        return;
    }

    const list = toc.querySelector('ul');
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
} catch (error) {
    console.warn('Blog enhancement scripts skipped.', error);
}
</script>
@endpush
