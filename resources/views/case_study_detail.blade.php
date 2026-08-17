@extends('layouts.master')

@php
    $caseStudyBrand = data_get($infor, 'name', 'Personal Brand');
    $caseStudyDescription = trim((string) $caseStudy->desc_seo) ?: \Illuminate\Support\Str::limit(
        strip_tags($caseStudy->summary ?: ($caseStudy->content ?? '')),
        155
    );
    $caseStudySeoTitle = trim((string) $caseStudy->title_seo) ?: ($caseStudy->title.' | '.$caseStudyBrand);
    $caseStudyMetadata = array_filter([
        'Khách hàng' => $caseStudy->client,
        'Ngành' => $caseStudy->industry,
        'Nền tảng' => $caseStudy->platforms,
        'Thời gian' => $caseStudy->duration,
        'Vai trò' => $caseStudy->role,
    ], fn ($value) => filled($value));
    $caseStudyKpis = collect(range(1, 4))->map(function ($number) use ($caseStudy) {
        return [
            'label' => data_get($caseStudy, 'kpi_'.$number.'_label'),
            'value' => data_get($caseStudy, 'kpi_'.$number.'_value'),
        ];
    })->filter(fn ($kpi) => filled($kpi['label']) && filled($kpi['value']));
    $caseStudySchema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $caseStudy->title,
        'description' => $caseStudyDescription,
        'image' => $caseStudy->image ? $caseStudy->image_url : null,
        'datePublished' => optional($caseStudy->created_at)->toIso8601String(),
        'dateModified' => optional($caseStudy->updated_at)->toIso8601String(),
        'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => url()->current()],
    ]);
@endphp
@section('body_class', 'is-article')
@section('page_title', $caseStudySeoTitle)
@section('meta_description', $caseStudyDescription)
@section('og_title', $caseStudy->title)
@section('og_description', $caseStudyDescription)
@section('og_type', 'article')
@section('canonical', url()->current())
@if($caseStudy->image)
    @section('og_image', $caseStudy->image_url)
@endif
@push('structured_data')
<script type="application/ld+json">{!! json_encode($caseStudySchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
@endpush

@section('content')
@if(!empty($isPreview) && $isPreview)
<div class="container"><div class="alert alert-warning mt-3" role="status">Bản xem trước — case study chưa đăng</div></div>
@endif
<article class="article-page">
    <div class="container">
        <header class="article-hero">
            <div class="article-shell article-shell--title">
                @include('partials.breadcrumbs', ['items' => [
                    ['name' => 'Trang chủ', 'url' => route('index')],
                    ['name' => 'Case Study', 'url' => route('portfolio')],
                    ['name' => $caseStudy->title],
                ]])
                <div class="article-hero__eyebrow"><span>Case Study</span></div>
                <h1 class="article-hero__title">{{ $caseStudy->title }}</h1>
                @if($caseStudy->summary)<p class="article-hero__subtitle">{{ $caseStudy->summary }}</p>@endif
            </div>
            @if($caseStudy->image)
            <figure class="article-hero__cover"><img src="{{ $caseStudy->image_url }}" alt="{{ $caseStudy->title }}" fetchpriority="high" decoding="async"></figure>
            @endif
        </header>

        @if($caseStudyMetadata)
        <dl class="case-study-meta article-shell">
            @foreach($caseStudyMetadata as $label => $value)
            <div><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>
            @endforeach
        </dl>
        @endif

        @if($caseStudyKpis->isNotEmpty())
        <section class="case-study-kpis article-shell" aria-label="Kết quả nổi bật">
            @foreach($caseStudyKpis as $kpi)
            <div class="case-study-kpis__item"><strong>{{ $kpi['value'] }}</strong><span>{{ $kpi['label'] }}</span></div>
            @endforeach
        </section>
        @endif

        <div class="news-content">{!! $caseStudy->content !!}</div>

        <footer class="article-footer">
            <div class="case-study-cta article-shell">
                <h2>Bạn muốn kết quả tương tự?</h2>
                <p>Hãy chia sẻ mục tiêu của bạn để nhận hướng đi phù hợp.</p>
                <a href="{{ route('contact') }}" class="theme-btn">Liên hệ tư vấn <i class="fa-solid fa-arrow-up-right"></i></a>
            </div>
        </footer>
    </div>
</article>
@endsection
