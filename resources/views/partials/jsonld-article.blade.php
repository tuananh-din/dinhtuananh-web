@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $blog->title ?? null,
        'description' => $blog->description
            ?: \Illuminate\Support\Str::limit(strip_tags($blog->content ?? ''), 155),
        'image' => $blog->image_url ?? null,
        'datePublished' => optional($blog->created_at)->toIso8601String(),
        'dateModified' => optional($blog->updated_at)->toIso8601String(),
        'author' => array_filter([
            '@type' => 'Person',
            'name' => data_get($infor, 'name') ?: data_get($contact, 'name'),
        ], function ($value) {
            return !is_null($value) && $value !== '';
        }),
        'mainEntityOfPage' => array_filter([
            '@type' => 'WebPage',
            '@id' => url()->current(),
        ], function ($value) {
            return !is_null($value) && $value !== '';
        }),
    ];

    $schema = array_filter($schema, function ($value) {
        if (is_array($value)) {
            return !empty($value);
        }

        return !is_null($value) && $value !== '';
    });
@endphp
<script type="application/ld+json">@json($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
