<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
    <channel>
        <title>{{ e(data_get($infor, 'name', config('app.name'))) }}</title>
        <link>{{ e(url('/blog')) }}</link>
        <description>{{ e(data_get($infor, 'desc_seo', 'Blog')) }}</description>
        @foreach($blogs as $blog)
            <item>
                <title>{{ e($blog->title) }}</title>
                <link>{{ e(route('blog', $blog->slug)) }}</link>
                <guid>{{ e(route('blog', $blog->slug)) }}</guid>
                <description>{{ e(\Illuminate\Support\Str::limit(strip_tags($blog->description ?: $blog->content), 300)) }}</description>
                <pubDate>{{ optional($blog->created_at)->toRssString() }}</pubDate>
            </item>
        @endforeach
    </channel>
</rss>
