@php
    $siteInfo = $infor ?? null;
    $contactInfo = $contact ?? null;
    $siteLogo = data_get($siteInfo, 'logo', 'app/assets/images/others/thumb-16.jpg');
    $brandName = data_get($contactInfo, 'name') ?: data_get($siteInfo, 'name', 'Personal Brand');
    $siteSlogan = data_get($siteInfo, 'slogan', 'Xây dựng thương hiệu cá nhân, chia sẻ kiến thức ads và tư vấn chiến lược thực chiến.');
    $phone = data_get($contactInfo, 'tel', '');
    $email = data_get($contactInfo, 'email', '');
@endphp
<footer class="footer-section fix pb-0">
    <div class="container">
        <div class="footer-wrapper wow fadeInUp" data-wow-delay=".3s">
            <ul class="footer-menu-list">
                <li><a href="{{ route('index') }}">Trang ch&#7911;</a></li>
                <li><a href="{{ route('about') }}">Gi&#7899;i thi&#7879;u</a></li>
                <li><a href="{{ route('portfolio') }}">Case Study</a></li>
                <li><a href="{{ route('blogs') }}">Blog</a></li>
                <li><a href="{{ route('courses') }}">Kh&#243;a h&#7885;c</a></li>
                <li><a href="{{ route('contact') }}">Li&#234;n h&#7879;</a></li>
            </ul>
            <div class="footer-brand-copy">
                <h3>{{ $brandName }}</h3>
                <p>{{ $siteSlogan }}</p>
                <a href="{{ route('index') }}#final-cta" class="theme-btn">Nh&#7853;n t&#432; v&#7845;n kh&#243;a h&#7885;c <i class="fa-solid fa-arrow-up-right"></i></a>
            </div>
            <div class="icon-items-area">
                @if(data_get($contactInfo, 'facebook'))
                <div class="icon-items">
                    <a href="{{ data_get($contactInfo, 'facebook') }}" class="icon">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="{{ data_get($contactInfo, 'facebook') }}">Facebook</a>
                </div>
                @endif
                @if(data_get($contactInfo, 'instagram'))
                <div class="icon-items">
                    <a href="{{ data_get($contactInfo, 'instagram') }}" class="icon">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="{{ data_get($contactInfo, 'instagram') }}">Instagram</a>
                </div>
                @endif
                @if(data_get($contactInfo, 'x'))
                <div class="icon-items">
                    <a href="{{ data_get($contactInfo, 'x') }}" class="icon">
                        <i class="fa-brands fa-twitter"></i>
                    </a>
                    <a href="{{ data_get($contactInfo, 'x') }}">X</a>
                </div>
                @endif
                @if(data_get($contactInfo, 'linkedin'))
                <div class="icon-items">
                    <a href="{{ data_get($contactInfo, 'linkedin') }}" class="icon">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </a>
                    <a href="{{ data_get($contactInfo, 'linkedin') }}">LinkedIn</a>
                </div>
                @endif
            </div>
        </div>
        <div class="footer-bottom wow fadeInUp" data-wow-delay=".3s">
            <p>Copyright &copy; <span>{{ data_get($siteInfo, 'name', $brandName) }}</span></p>
            <a href="{{ route('index') }}" class="footer-logo"><img src="{{ $siteLogo }}" alt="logo" height="100px"></a>
            <ul>
                <li><a href="tel:{{ $phone }}">Hotline: {{ $phone }}</a></li>
                <li><a href="mailto:{{ $email }}">{{ $email }}</a></li>
            </ul>
        </div>
    </div>
</footer>
