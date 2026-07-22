@php
    $siteInfo = $infor ?? null;
    $contactInfo = $contact ?? null;
    $siteLogo = data_get($siteInfo, 'logo', 'app/assets/images/others/thumb-16.jpg');
    // Logo có thể là URL tuyệt đối, đường dẫn "/storage/..." hoặc chuỗi tương đối.
    // Chỉ bọc asset() khi là đường dẫn tương đối, tránh lặp URL với các giá trị đã tuyệt đối.
    $siteLogoUrl = \Illuminate\Support\Str::startsWith($siteLogo, ['http://', 'https://', '//', '/'])
        ? $siteLogo
        : asset($siteLogo);
    $brandName = data_get($contactInfo, 'name') ?: data_get($siteInfo, 'name', 'Brand');
    $phone = data_get($contactInfo, 'tel', '');
    $email = data_get($contactInfo, 'email', '');
@endphp
<!-- Preloader Start -->
<div class="preloader">
    <svg viewBox="0 0 1000 1000" preserveAspectRatio="none">
        <path id="svg" d="M0,1005S175,995,500,995s500,5,500,5V0H0Z"></path>
    </svg>
    <h5 class="preloader-text">{{ $brandName }}</h5>
</div>

<!-- Back To Top Start -->
<button id="back-top" class="back-to-top">
    <i class="fa-regular fa-arrow-up"></i>
</button>

<!-- GT MouseCursor Start -->
<div class="mouseCursor cursor-outer"></div>
<div class="mouseCursor cursor-inner"></div>

<!-- Offcanvas Area Start -->
<div class="fix-area style-offcanvas-2">
    <div class="header-offcanvas-border">
        <div class="offcanvas__info">
            <a href="{{ route('index') }}" class="offcanvas__logo">
                <img src="{{ $siteLogoUrl }}" height="100px" alt="logo">
            </a>
            <div class="offcanvas__close">
                <button>
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="contact-information">
                <h3>Nh&#7853;n t&#432; v&#7845;n nhanh</h3>
                <ul class="contact-list">
                    <li>
                        <span>Phone / Zalo</span>
                        <a href="tel:{{ $phone }}">{{ $phone }}</a>
                    </li>
                    <li>
                        <span>Email</span>
                        <a href="mailto:{{ $email }}">{{ $email }}</a>
                    </li>
                    <li>
                        <span>CTA</span>
                        <a href="{{ route('index') }}#final-cta">&#272;&#259;ng k&#253; h&#7885;c ngay</a>
                    </li>
                    <li>
                        <span>Follow me</span>
                        <div class="social-icon">
                            @if(data_get($contactInfo, 'facebook'))<a href="{{ data_get($contactInfo, 'facebook') }}"><i class="fab fa-facebook-f"></i></a>@endif
                            @if(data_get($contactInfo, 'instagram'))<a href="{{ data_get($contactInfo, 'instagram') }}"><i class="fab fa-instagram"></i></a>@endif
                            @if(data_get($contactInfo, 'x'))<a href="{{ data_get($contactInfo, 'x') }}"><i class="fab fa-twitter"></i></a>@endif
                            @if(data_get($contactInfo, 'linkedin'))<a href="{{ data_get($contactInfo, 'linkedin') }}"><i class="fab fa-linkedin-in"></i></a>@endif
                        </div>
                    </li>
                </ul>
            </div>
            <div class="offcanvas__wrapper">
                <div class="offcanvas__content">
                    <div class="mobile-menus fix"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="offcanvas__overlay"></div>

<!-- Header Section Start -->
<header id="header-sticky" class="header-1">
    <div class="container">
        <div class="mega-menu-wrapper">
            <div class="header-main">
                <a href="{{ route('index') }}" class="offcanvas__logo">
                    <img src="{{ $siteLogoUrl }}" alt="logo">
                </a>
                <div class="header-right justify-content-end align-items-center">
                    <div class="mean__menu-wrapper d-none d-lg-block">
                        <div class="main-menu">
                            <nav id="mobile-menus">
                                <ul>
                                    <li><a href="{{ route('index') }}">Trang ch&#7911;</a></li>
                                    <li><a href="{{ route('about') }}">Gi&#7899;i thi&#7879;u</a></li>
                                    <li><a href="{{ route('portfolio') }}">Case Study</a></li>
                                    <li><a href="{{ route('blogs') }}">Blog</a></li>
                                    <li><a href="{{ route('courses') }}">Kh&#243;a h&#7885;c</a></li>
                                    <li><a href="{{ route('about') }}#contact-brand">Li&#234;n h&#7879;</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <a href="{{ route('index') }}#final-cta" class="theme-btn">&#272;&#259;ng k&#253; h&#7885;c <i class="fa-solid fa-arrow-up-right"></i></a>
                    <div class="header__hamburger my-auto">
                        <div class="sidebar__toggle">
                            <img src="{{ asset('site/assets/img/bar.svg') }}" alt="menu">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
