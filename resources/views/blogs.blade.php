@extends('layouts.master')
@section('page_title', 'Blog | ' . data_get($infor, 'name', 'Personal Brand'))
@section('og_title', 'Blog | ' . data_get($infor, 'name', 'Personal Brand'))
@section('content')
<section class="news-grid-section1 fix">
    <div class="container">
        @if($selectedCategory)
            @php($selectedCategoryModel = $categories->firstWhere('slug', $selectedCategory))
            @include('partials.breadcrumbs', ['items' => [
                ['name' => 'Trang chủ', 'url' => route('index')],
                ['name' => 'Blog', 'url' => route('blogs')],
                ['name' => optional($selectedCategoryModel)->name ?: $selectedCategory],
            ]])
        @endif
        <h1>Blog</h1>
        <form method="GET" action="{{ route('blogs') }}" class="mb-4" aria-label="Lọc bài viết">
            <label for="blog-search" class="visually-hidden">Tìm bài viết</label>
            <input id="blog-search" name="search" value="{{ $search }}" placeholder="Tìm bài viết" aria-label="Tìm bài viết">
            <label for="blog-category" class="visually-hidden">Chuyên mục</label>
            <select id="blog-category" name="category" aria-label="Chuyên mục">
                <option value="">Tất cả chuyên mục</option>
                @foreach($categories as $category)<option value="{{ $category->slug }}" {{ $selectedCategory === $category->slug ? 'selected' : '' }}>{{ $category->name }}</option>@endforeach
            </select>
            <button class="theme-btn" type="submit">Lọc</button>
        </form>
        @if($blogs->isEmpty())
        <div class="blog-empty-state text-center py-5">
            @if($search || $selectedCategory)
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
        <div class="row bb-bottom">
            @foreach ($blogs as $key => $row)
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".{{ ($key + 1) * 2 }}s">
                <div class="news-box-items-2">
                    <div class="thumb">
                        <a href="{{ route('blog', $row->slug) }}">
                            <img src="{{ $row->image_url }}" alt="{{ $row->title }}" loading="lazy">
                        </a>
                    </div>
                    <div class="content">
                        <span class="post-date">{{ optional($row->created_at)->format('d/m/Y') }}</span>
                        @if($row->categories->isNotEmpty())
                        <div class="blog-category-list" aria-label="Chuyên mục">
                            @foreach($row->categories as $category)
                            <a class="blog-category-chip" href="{{ route('blogs', ['category' => $category->slug]) }}">{{ $category->name }}</a>
                            @endforeach
                        </div>
                        @endif
                        <h3><a href="{{ route('blog', $row->slug) }}">{{ $row->title }}</a></h3>
                        @if($row->description)
                        <p>{{ \Illuminate\Support\Str::limit($row->description, 160) }}</p>
                        @endif
                        <a href="{{ route('blog', $row->slug) }}" class="theme-btn">&#272;&#7885;c b&#224;i vi&#7871;t <i class="fa-solid fa-arrow-up-right"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        <div class="page-nav-wrap text-center">
            {!! $blogs->links('vendor.paginate') !!}
        </div>
    </div>
</section>
@endsection
