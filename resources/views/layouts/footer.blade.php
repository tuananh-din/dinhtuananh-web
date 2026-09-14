@php
    $siteInfo = $infor ?? null;
    $contactInfo = $contact ?? null;
    $siteLogo = data_get($siteInfo, 'logo', 'app/assets/images/others/thumb-16.jpg');
    $brandName = data_get($contactInfo, 'name') ?: data_get($siteInfo, 'name', 'Personal Brand');
    $siteSlogan = data_get($siteInfo, 'slogan', 'Xây dựng thương hiệu cá nhân, chia sẻ kiến thức ads và tư vấn chiến lược thực chiến.');
    $siteLogoUrl = \Illuminate\Support\Str::startsWith($siteLogo, ['http://', 'https://', '//', '/'])
        ? $siteLogo
        : asset($siteLogo);
    $phone = data_get($contactInfo, 'tel', '');
    $email = data_get($contactInfo, 'email', '');
@endphp
<footer class="footer-section fix pb-0">
    <div class="container">
        <div class="footer-wrapper wow fadeInUp" data-wow-delay=".3s">
            <nav aria-label="Điều hướng chân trang">
            <ul class="footer-menu-list">
                <li><a href="{{ route('index') }}">Trang ch&#7911;</a></li>
                <li><a href="{{ route('about') }}">Gi&#7899;i thi&#7879;u</a></li>
                <li><a href="{{ route('portfolio') }}">Case Study</a></li>
                <li><a href="{{ route('blogs') }}">Blog</a></li>
                <li><a href="{{ route('courses') }}">Kh&#243;a h&#7885;c</a></li>
                <li><a href="{{ route('contact') }}">Li&#234;n h&#7879;</a></li>
            </ul>
            </nav>
            <div class="footer-brand-copy">
                <h3><a href="{{ route('index') }}">{{ $brandName }}</a></h3>
                <p>{{ $siteSlogan }}</p>
                <a href="{{ route('index') }}#final-cta" class="theme-btn">Nh&#7853;n t&#432; v&#7845;n kh&#243;a h&#7885;c <i class="fa-solid fa-arrow-up-right"></i></a>
                <form action="{{ route('newsletter.store') }}" method="POST" class="mt-4" data-submit-label="Đang gửi...">
                    @csrf
                    <input type="hidden" name="source" value="footer">
                    <input type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true" style="display:none">
                    <label for="footer-newsletter-email">Nhận kiến thức mới qua email</label>
                    @php($footerNewsletterErrors = $errors->getBag('newsletterFooter'))
                    @if(data_get(session('notice'), 'context') === 'newsletter-footer')
                        @include('partials.notice-banner', array_merge(session('notice'), ['dismissible' => true]))
                    @endif
                    @if($footerNewsletterErrors->any())
                        @include('partials.notice-banner', ['type' => 'error', 'title' => 'Vui lòng kiểm tra email', 'messages' => $footerNewsletterErrors->all()])
                    @endif
                    <div class="d-flex gap-2">
                        <input id="footer-newsletter-email" type="email" name="email" required class="form-control @error('email', 'newsletterFooter') is-invalid @enderror" value="{{ old('email') }}" autocomplete="email" inputmode="email" placeholder="Ví dụ: ten@domain.com" aria-describedby="footer-newsletter-hint @error('email', 'newsletterFooter') footer-newsletter-error @enderror" @error('email', 'newsletterFooter') aria-invalid="true" @enderror>
                        <button type="submit" class="theme-btn">Đăng ký</button>
                    </div>
                    <p id="footer-newsletter-hint" class="form-field-hint">Chúng tôi chỉ dùng email này để gửi kiến thức mới.</p>
                    @error('email', 'newsletterFooter')<p id="footer-newsletter-error" class="form-field-error" role="alert">{{ $message }}</p>@enderror
                </form>
            </div>
            <div class="icon-items-area">
                @if(data_get($contactInfo, 'facebook'))
                <div class="icon-items">
                    <a href="{{ data_get($contactInfo, 'facebook') }}" class="icon-items__link" aria-label="Theo dõi {{ $brandName }} trên Facebook">
                        <span class="icon" aria-hidden="true">
                        <i class="fa-brands fa-facebook-f"></i>
                        </span>
                        <span>Facebook</span>
                    </a>
                </div>
                @endif
                @if(data_get($contactInfo, 'instagram'))
                <div class="icon-items">
                    <a href="{{ data_get($contactInfo, 'instagram') }}" class="icon-items__link" aria-label="Theo dõi {{ $brandName }} trên Instagram">
                        <span class="icon" aria-hidden="true">
                        <i class="fa-brands fa-instagram"></i>
                        </span>
                        <span>Instagram</span>
                    </a>
                </div>
                @endif
                @if(data_get($contactInfo, 'x'))
                <div class="icon-items">
                    <a href="{{ data_get($contactInfo, 'x') }}" class="icon-items__link" aria-label="Theo dõi {{ $brandName }} trên X">
                        <span class="icon" aria-hidden="true">
                        <i class="fa-brands fa-twitter"></i>
                        </span>
                        <span>X</span>
                    </a>
                </div>
                @endif
                @if(data_get($contactInfo, 'linkedin'))
                <div class="icon-items">
                    <a href="{{ data_get($contactInfo, 'linkedin') }}" class="icon-items__link" aria-label="Theo dõi {{ $brandName }} trên LinkedIn">
                        <span class="icon" aria-hidden="true">
                        <i class="fa-brands fa-linkedin-in"></i>
                        </span>
                        <span>LinkedIn</span>
                    </a>
                </div>
                @endif
            </div>
        </div>
        <div class="footer-bottom wow fadeInUp" data-wow-delay=".3s">
            <p>&copy; {{ now()->year }} <span>{{ data_get($siteInfo, 'name', $brandName) }}</span></p>
            <a href="{{ route('index') }}" class="footer-logo" aria-label="Về trang chủ {{ $brandName }}"><img src="{{ $siteLogoUrl }}" alt="{{ $brandName }}" height="100px"></a>
            <ul>
                @if($phone)
                <li><a href="tel:{{ $phone }}">Hotline: {{ $phone }}</a></li>
                @endif
                @if($email)
                <li><a href="mailto:{{ $email }}">{{ $email }}</a></li>
                @endif
                @if(!$phone && !$email)
                <li><a href="{{ route('contact') }}">Liên hệ tư vấn</a></li>
                @endif
            </ul>
        </div>
    </div>
</footer>
