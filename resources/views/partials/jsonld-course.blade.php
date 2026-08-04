@php
    $price = !is_null($course->sale_price) ? $course->sale_price : $course->price;
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Course',
        'name' => $course->title,
        'description' => $course->short_description
            ?: \Illuminate\Support\Str::limit(strip_tags($course->description ?: $course->content), 155),
        'provider' => [
            '@type' => 'Organization',
            'name' => data_get($infor, 'name', 'Personal Brand'),
        ],
    ];

    if (!is_null($price)) {
        $schema['offers'] = [
            '@type' => 'Offer',
            'price' => (string) $price,
            'priceCurrency' => 'VND',
            'availability' => 'https://schema.org/InStock',
            'url' => url()->current(),
        ];
    }
@endphp
<script type="application/ld+json">@json($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)</script>
