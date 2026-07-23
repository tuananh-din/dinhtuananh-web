@php
    $person = $about ?? null;
    $avatar = data_get($person, 'avatar');

    if ($avatar && !\Illuminate\Support\Str::startsWith($avatar, ['http://', 'https://', '//', '/'])) {
        $avatar = asset(ltrim($avatar, '/'));
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => data_get($person, 'name') ?: data_get($infor, 'name'),
        'url' => url('/'),
        'image' => $avatar,
        'jobTitle' => data_get($person, 'description'),
        'description' => data_get($person, 'about_me') ?: data_get($person, 'content'),
        'sameAs' => array_values(array_filter([
            data_get($person, 'facebook'),
            data_get($person, 'instagram'),
            data_get($person, 'linkedin'),
            data_get($person, 'x'),
        ])),
    ];

    $schema = array_filter($schema, function ($value) {
        if (is_array($value)) {
            return !empty($value);
        }

        return !is_null($value) && $value !== '';
    });
@endphp
<script type="application/ld+json">@json($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)</script>
