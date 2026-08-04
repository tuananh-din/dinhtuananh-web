@php
    $staticPages = [
        ['loc' => route('index'), 'lastmod' => now()->toAtomString()],
        ['loc' => route('about'), 'lastmod' => now()->toAtomString()],
        ['loc' => route('life'), 'lastmod' => now()->toAtomString()],
        ['loc' => route('portfolio'), 'lastmod' => now()->toAtomString()],
        ['loc' => route('contact'), 'lastmod' => now()->toAtomString()],
        ['loc' => route('blogs'), 'lastmod' => now()->toAtomString()],
        ['loc' => route('courses'), 'lastmod' => now()->toAtomString()],
    ];
@endphp
{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($staticPages as $page)
    <url>
        <loc>{{ $page['loc'] }}</loc>
        <lastmod>{{ $page['lastmod'] }}</lastmod>
    </url>
@endforeach
@foreach ($blogs as $blog)
    <url>
        <loc>{{ route('blog', $blog->slug) }}</loc>
        <lastmod>{{ optional($blog->updated_at)->toAtomString() ?? now()->toAtomString() }}</lastmod>
    </url>
@endforeach
@foreach ($blogCategories as $category)
    <url>
        <loc>{{ route('blogs', ['category' => $category->slug]) }}</loc>
        <lastmod>{{ optional($category->updated_at)->toAtomString() ?? now()->toAtomString() }}</lastmod>
    </url>
@endforeach
@foreach ($courses as $course)
    <url>
        <loc>{{ route('course.detail', $course->slug) }}</loc>
        <lastmod>{{ optional($course->updated_at)->toAtomString() ?? now()->toAtomString() }}</lastmod>
    </url>
@endforeach
</urlset>
