@extends('layouts.master')

@php
    $siteName = filled(data_get($infor, 'name')) ? data_get($infor, 'name') : 'Personal Brand';
    $pageTitle = 'Blog Digital Marketing, Performance & Data | ' . $siteName;
    $pageDescription = 'Góc chia sẻ về Digital Marketing, Performance Marketing, dữ liệu và đào tạo: tư duy, cách triển khai và bài học từ thực tế.';
    $hasSearch = $search !== '';
    $hasValidCategory = !is_null($selectedCategory);
    $isInvalidCategory = $categorySlug !== '' && !$hasValidCategory;
    $canonicalParameters = [];

    if (!$hasSearch && !$isInvalidCategory) {
        if ($hasValidCategory) {
            $canonicalParameters['category'] = $selectedCategory->slug;
        }

        if ($blogs->currentPage() > 1 && $blogs->currentPage() <= $blogs->lastPage()) {
            $canonicalParameters['page'] = $blogs->currentPage();
        }
    }

    $resultHeading = $hasSearch
        ? 'Kết quả tìm kiếm cho “' . $search . '”'
        : ($hasValidCategory ? 'Bài viết về ' . $selectedCategory->name : ($isInvalidCategory ? 'Kết quả lọc bài viết' : 'Bài viết mới nhất'));
@endphp

@section('page_title', $pageTitle)
@section('meta_description', $pageDescription)
@section('og_title', $pageTitle)
@section('og_description', $pageDescription)
@section('canonical', route('blogs', $canonicalParameters))
@section('body_class', 'blog-page')

@section('content')
<section class="blog-page__hero" aria-labelledby="blog-page-title">
    <div class="container">
        @if($hasValidCategory)
            @include('partials.breadcrumbs', ['items' => [
                ['name' => 'Trang chủ', 'url' => route('index')],
                ['name' => 'Blog', 'url' => route('blogs')],
                ['name' => $selectedCategory->name],
            ]])
        @endif
        <p class="blog-page__eyebrow">GÓC CHIA SẺ CHUYÊN MÔN</p>
        <h1 id="blog-page-title">Digital Marketing, Performance &amp; Data</h1>
        <p class="blog-page__lead">Các bài viết về tư duy, cách triển khai và bài học thực tế trong Digital Marketing, Performance Marketing, dữ liệu và đào tạo.</p>
    </div>
</section>

<section class="blog-page__discovery" aria-labelledby="blog-discovery-title">
    <div class="container">
        <div class="blog-page__section-heading">
            <h2 id="blog-discovery-title">Tìm nội dung bạn cần</h2>
            <p>Dùng từ khóa hoặc chọn một chủ đề đang có bài viết công khai.</p>
        </div>
        <form method="GET" action="{{ route('blogs') }}" class="blog-page__filters" role="search">
            <div class="blog-page__filter-field blog-page__filter-field--search">
                <label for="blog-search">Tìm bài viết</label>
                <input id="blog-search" type="search" name="search" value="{{ $search }}" placeholder="Tìm theo chủ đề hoặc từ khóa…" autocomplete="off">
            </div>
            <div class="blog-page__filter-field">
                <label for="blog-category">Chủ đề</label>
                <select id="blog-category" name="category">
                    <option value="">Tất cả chủ đề</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->slug }}" {{ $hasValidCategory && $selectedCategory->slug === $category->slug ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <button class="theme-btn" type="submit">Tìm bài viết <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></button>
            @if($hasSearch || $categorySlug !== '')
            <a href="{{ route('blogs') }}" class="blog-page__reset">Xóa bộ lọc</a>
            @endif
        </form>
    </div>
</section>

<section class="blog-page__listing" aria-labelledby="blog-results-title">
    <div class="container">
        <div class="blog-page__listing-heading">
            <p class="blog-page__eyebrow">BÀI VIẾT</p>
            <h2 id="blog-results-title" aria-live="polite">{{ $resultHeading }}</h2>
        </div>
        @if($blogs->isEmpty())
        <div class="blog-page__empty blog-empty-state blog-sparse-empty">
            @if($hasSearch || $categorySlug !== '')
                <h2>Chưa có bài viết trong mục này.</h2>
                <p>Hãy thử từ khóa hoặc chuyên mục khác.</p>
                <a href="{{ route('blogs') }}" class="theme-btn">Xem tất cả bài viết</a>
            @else
                <h2>Chưa có bài viết mới.</h2>
                <p>Nội dung đang được chuẩn bị. Bạn có thể xem các khóa học hiện có.</p>
                <a href="{{ route('courses') }}" class="theme-btn">Xem khóa học</a>
            @endif
        </div>
        @else
        <div class="row g-4 blog-page__grid blog-list-grid {{ $blogs->count() === 1 ? 'blog-list-grid--single' : '' }}">
            @foreach ($blogs as $key => $row)
            @php
                $excerptSource = filled($row->description) ? $row->description : ($row->content ?? '');
                $excerpt = trim(strip_tags($excerptSource));
            @endphp
            <div class="col-xl-4 col-md-6 d-flex">
                <article class="blog-page__card">
                    @if(filled($row->image))
                    <div class="thumb">
                        <a href="{{ route('blog', $row->slug) }}">
                            <img src="{{ $row->image_url }}" alt="{{ $row->title }}" width="1200" height="675" loading="lazy" decoding="async">
                        </a>
                    </div>
                    @endif
                    <div class="content">
                        @if($row->categories->isNotEmpty())
                        <ul class="blog-category-list" aria-label="Chuyên mục">
                            @foreach($row->categories as $category)
                            <li><a class="blog-category-chip" href="{{ route('blogs', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                        @endif
                        @if($row->created_at)<time class="post-date" datetime="{{ $row->created_at->toDateString() }}">{{ $row->created_at->format('d/m/Y') }}</time>@endif
                        <h3><a href="{{ route('blog', $row->slug) }}">{{ $row->title }}</a></h3>
                        @if($excerpt !== '')
                        <p>{{ \Illuminate\Support\Str::limit($excerpt, 155) }}</p>
                        @endif
                        <a href="{{ route('blog', $row->slug) }}" class="theme-btn">Đọc bài viết <span class="visually-hidden">: {{ $row->title }}</span><i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>
                    </div>
                </article>
            </div>
            @endforeach
        </div>
        @if($blogs->hasPages())
        <nav class="page-nav-wrap text-center" aria-label="Phân trang bài viết">
            {!! $blogs->links('vendor.pagination') !!}
        </nav>
        @endif
        <aside class="blog-page__course-cta" aria-label="Khóa học Digital Marketing">
            <p>Muốn hệ thống hóa kiến thức theo lộ trình? Khám phá các khóa học phù hợp.</p>
            <a href="{{ route('courses') }}" class="theme-btn border-btn">Xem khóa học <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i></a>
        </aside>
        @endif
    </div>
</section>
@endsection
