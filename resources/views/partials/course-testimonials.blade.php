@if($testimonials->isNotEmpty())
<section class="dpm-proof" aria-labelledby="dpm-proof-title">
    <div class="dpm-wrap">
        <div class="dpm-proof-head">
            <p class="dpm-eyebrow">PHẢN HỒI TỪ HỌC VIÊN</p>
            <h2 id="dpm-proof-title">Học viên nói gì</h2>
        </div>
        <div class="dpm-proof-grid">
            @foreach($testimonials as $testimonial)
            <article class="dpm-proof-card">
                @if($testimonial->rating)
                <p class="dpm-proof-rating" aria-label="{{ $testimonial->rating }} trên 5 sao">
                    @for($i = 0; $i < $testimonial->rating; $i++)<span aria-hidden="true">★</span>@endfor
                </p>
                @endif
                <p class="dpm-proof-quote">“{{ $testimonial->content }}”</p>
                <p class="dpm-proof-name">{{ $testimonial->name }}</p>
                <p class="dpm-proof-role">{{ $testimonial->job_title ?: 'Học viên / Khách hàng' }}@if($testimonial->company) · {{ $testimonial->company }}@endif</p>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif
