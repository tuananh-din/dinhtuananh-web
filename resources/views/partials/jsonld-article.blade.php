@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $blog->title ?? null,
        // Cung chuoi fallback voi <meta name="description"> o blog_detail.blade.php:
        // desc_seo -> description -> trich tu content.
        'description' => trim((string) ($blog->desc_seo ?? '')) ?: ($blog->description
            ?: \Illuminate\Support\Str::limit(strip_tags($blog->content ?? ''), 155)),
        'image' => $blog->image_url ?? null,
        'datePublished' => optional($blog->created_at)->toIso8601String(),
        'dateModified' => optional($blog->updated_at)->toIso8601String(),
        'author' => array_filter([
            '@type' => 'Person',
            // Uu tien bang `about` (nguoi that) truoc `setting` (ten site), de khop
            // voi ten tac gia hien o hero cua bai viet.
            'name' => data_get($contact, 'name') ?: data_get($infor, 'name'),
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
<script type="application/ld+json">@json($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)</script>
