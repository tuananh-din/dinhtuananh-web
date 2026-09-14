@php
    $siteInfo = $infor ?? null;
    $seoDescription = data_get($siteInfo, 'desc_seo', '');
    $siteName = data_get($siteInfo, 'name', 'Personal Brand');
    $favicon = data_get($siteInfo, 'favicon', 'app/assets/images/others/thumb-16.jpg');
    // Nếu favicon là URL tuyệt đối hoặc đã bắt đầu bằng "/" (VD "/storage/...") thì dùng nguyên,
    // ngược lại (đường dẫn tương đối) thì bọc asset() để không vỡ trên route 2 cấp.
    $faviconUrl = \Illuminate\Support\Str::startsWith($favicon, ['http://', 'https://', '//', '/'])
        ? $favicon
        : asset($favicon);
    $pageOgImage = trim((string) $__env->yieldContent('og_image', ''));
    $defaultOgImage = trim((string) data_get($siteInfo, 'og_image', ''));
    $selectedOgImage = $pageOgImage ?: $defaultOgImage;
    $ogImageUrl = null;
    if ($selectedOgImage !== '') {
        $ogImageUrl = \Illuminate\Support\Str::startsWith($selectedOgImage, ['http://', 'https://'])
            ? $selectedOgImage
            : (\Illuminate\Support\Str::startsWith($selectedOgImage, '//')
                ? request()->getScheme().':'.$selectedOgImage
                : asset(ltrim($selectedOgImage, '/')));
    }
    $assetVersion = static function (string $path): string {
        $file = public_path($path);
        return asset($path).(is_file($file) ? '?v='.filemtime($file) : '');
    };
@endphp
<!DOCTYPE html>
<html lang="vi">
    <!--<< Header Area >>-->
    <head>
        <!-- ========== Meta Tags ========== -->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ trim($__env->yieldContent('meta_description', $seoDescription)) }}">
        <!-- ======== Page title ============ -->
        <title>{{ trim($__env->yieldContent('page_title', $siteName)) }}</title>
        <!-- ========== Open Graph ========== -->
        <meta property="og:type" content="@yield('og_type', 'website')">
        <meta property="og:title" content="{{ trim($__env->yieldContent('og_title', $siteName)) }}">
        <meta property="og:description" content="{{ trim($__env->yieldContent('og_description', $seoDescription)) }}">
        @if($ogImageUrl)
        <meta property="og:image" content="{{ $ogImageUrl }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        @endif
        <meta property="og:url" content="{{ url()->current() }}">
        @if($ogImageUrl)
        <meta name="twitter:card" content="summary_large_image">
        @endif
        <meta name="twitter:title" content="{{ trim($__env->yieldContent('og_title', $siteName)) }}">
        <meta name="twitter:description" content="{{ trim($__env->yieldContent('og_description', $seoDescription)) }}">
        @if($ogImageUrl)
        <meta name="twitter:image" content="{{ $ogImageUrl }}">
        @endif
        <link rel="canonical" href="{{ trim($__env->yieldContent('canonical', url()->current())) }}">
        <link rel="alternate" type="application/rss+xml" title="{{ $siteName }} RSS" href="{{ route('feed') }}">
        <!--<< Favcion >>-->
        <link rel="shortcut icon" href="{{ $faviconUrl }}" type="image/x-icon" />
        {{-- Dark/Light: set data-theme SỚM (trước CSS) để tránh nháy sáng (FOUC). Ưu tiên lựa chọn đã lưu, fallback theo OS. --}}
        <script>
            (function () {
                try {
                    var t = localStorage.getItem('theme');
                    if (t !== 'dark' && t !== 'light') {
                        t = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                    }
                    document.documentElement.setAttribute('data-theme', t);
                } catch (e) {}
            })();
        </script>
        <script>
            (function () {
                var toggle = document.querySelector('.sidebar__toggle');
                var close = document.querySelector('.offcanvas__close button');
                var menu = document.getElementById('site-mobile-menu');
                if (!toggle || !menu) return;
                toggle.addEventListener('click', function () {
                    setTimeout(function () {
                        toggle.setAttribute('aria-expanded', menu.getAttribute('aria-hidden') === 'false' ? 'true' : 'false');
                    }, 0);
                });
                if (close) close.addEventListener('click', function () { toggle.setAttribute('aria-expanded', 'false'); });
            })();
        </script>
        <!--<< Bootstrap min.css >>-->
        <link rel="stylesheet" href="{{ asset('site/assets/css/bootstrap.min.css') }}">
        <!--<< All Min Css >>-->
        <link rel="stylesheet" href="{{ asset('site/assets/css/all.min.css') }}">
        <!--<< Animate.css >>-->
        <link rel="stylesheet" href="{{ asset('site/assets/css/animate.css') }}">
        <!--<< Magnific Popup.css >>-->
        <link rel="stylesheet" href="{{ asset('site/assets/css/magnific-popup.css') }}">
        <!--<< MeanMenu.css >>-->
        <link rel="stylesheet" href="{{ asset('site/assets/css/meanmenu.css') }}">
        <!--<< Swiper Bundle.css >>-->
        <link rel="stylesheet" href="{{ asset('site/assets/css/swiper-bundle.min.css') }}">
        <!--<< Nice Select.css >>-->
        <link rel="stylesheet" href="{{ asset('site/assets/css/nice-select.css') }}">
        <!--<< Main.css >>-->
        <link rel="stylesheet" href="{{ $assetVersion('site/assets/css/main.css') }}">
        <link rel="stylesheet" href="{{ $assetVersion('site/assets/css/custom.css') }}">
        {{-- Article Design System: nap SAU custom.css. Moi selector deu scope duoi
             `.article-page` / `body.is-article` (chi co o blog_detail.blade.php). --}}
        <link rel="stylesheet" href="{{ $assetVersion('site/assets/css/article.css') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">

        @stack('head')
        @stack('structured_data')
        {!! data_get($infor, 'code_header') !!}
    </head>
    <body class="@yield('body_class')">
        <a class="skip-to-content" href="#main-content">Bỏ qua tới nội dung</a>

        @include('layouts.header')
        <div id="smooth-wrapper">
            <div id="smooth-content">
                <main id="main-content" tabindex="-1">
                @yield('content')
                @include('layouts.footer')
                </main>
            </div>
        </div>

        {{-- Trang doc bai viet: an nut lien he noi de giam nhieu khi doc.
             Dieu kien bam theo ROUTE NAME nen landing/service/course/blog-listing
             khong bi anh huong. `blog` = /{slug}.html, `blog.preview` = preview Admin. --}}
        @unless(request()->routeIs('blog', 'blog.preview'))
            @include('layouts.floating-contact')
        @endunless

       

        
        <!--<< All JS Plugins >>-->
        <script src="{{ asset('site/assets/js/jquery-3.7.1.min.js') }}"></script>
        <!--<< Viewport Js >>-->
        <script src="{{ asset('site/assets/js/viewport.jquery.js') }}"></script>
        <!--<< Bootstrap Js >>-->
        <script src="{{ asset('site/assets/js/bootstrap.bundle.min.js') }}"></script>
        <!--<< Gsap Min Js >>-->
        <script src="{{ asset('site/assets/js/gsap.min.js') }}"></script>
        <!--<< ScrollTrigger Min Js >>-->
        <script src="{{ asset('site/assets/js/ScrollTrigger.min.js') }}"></script>
        <!--<< ScrollSmoother Min Js >>-->
        <script src="{{ asset('site/assets/js/ScrollSmoother.min.js') }}"></script>
        <!--<< ScrollToPlugin Min Js >>-->
        <script src="{{ asset('site/assets/js/ScrollToPlugin.min.js') }}"></script>
        <!--<< SplitText Min Js >>-->
        <script src="{{ asset('site/assets/js/SplitText.min.js') }}"></script>
        <!--<< TextPlugin Min Js >>-->
        <script src="{{ asset('site/assets/js/TextPlugin.js') }}"></script>
         <!--<< Chroma Min Js >>-->
        <script src="{{ asset('site/assets/js/chroma.min.js') }}"></script>
        <!--<< nice-selec Js >>-->
        <script src="{{ asset('site/assets/js/jquery.nice-select.min.js') }}"></script>
        <!--<< Waypoints Js >>-->
        <script src="{{ asset('site/assets/js/jquery.waypoints.js') }}"></script>
        <!--<< Counterup Js >>-->
        <script src="{{ asset('site/assets/js/jquery.counterup.min.js') }}"></script>
        <!--<< Swiper Slider Js >>-->
        <script src="{{ asset('site/assets/js/swiper-bundle.min.js') }}"></script>
        <!--<< MeanMenu Js >>-->
        <script src="{{ asset('site/assets/js/jquery.meanmenu.min.js') }}"></script>
        <!--<< Parallaxie Js >>-->
        <script src="{{ asset('site/assets/js/parallaxie.js') }}"></script>
        <!--<< Magnific Popup Js >>-->
        <script src="{{ asset('site/assets/js/jquery.magnific-popup.min.js') }}"></script>
        <!--<< Wow Animation Js >>-->
        <script src="{{ asset('site/assets/js/wow.min.js') }}"></script>
        <!--<< Main.js >>-->
        <script src="{{ $assetVersion('site/assets/js/main.js') }}"></script>
        {{-- Dark/Light toggle: đổi + lưu localStorage, cập nhật icon. --}}
        <script>
            (function () {
                var btn = document.getElementById('theme-toggle');
                if (!btn) return;
                var icon = btn.querySelector('i');
                var tooltip = document.getElementById('theme-toggle-tooltip');
                function sync() {
                    var dark = document.documentElement.getAttribute('data-theme') === 'dark';
                    if (icon) icon.className = dark ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
                    btn.setAttribute('aria-pressed', dark ? 'true' : 'false');
                    if (tooltip) tooltip.textContent = dark ? 'Chuyển sang giao diện sáng' : 'Chuyển sang giao diện tối';
                }
                function showTooltip() { if (tooltip) tooltip.hidden = false; }
                function hideTooltip() { if (tooltip) tooltip.hidden = true; }
                sync();
                btn.addEventListener('mouseenter', showTooltip);
                btn.addEventListener('mouseleave', hideTooltip);
                btn.addEventListener('focus', showTooltip);
                btn.addEventListener('blur', hideTooltip);
                btn.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        hideTooltip();
                        btn.blur();
                    }
                });
                btn.addEventListener('click', function () {
                    var next = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                    document.documentElement.setAttribute('data-theme', next);
                    try { localStorage.setItem('theme', next); } catch (e) {}
                    sync();
                    hideTooltip();
                });
            })();
        </script>
        {{-- Slot cho các view push script phụ thuộc jQuery/main.js đã nạp xong. --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var canMatchMedia = typeof window.matchMedia === 'function';
                var canUseEnhancedMotion = canMatchMedia
                    && !window.matchMedia('(prefers-reduced-motion: reduce)').matches
                    && window.matchMedia('(pointer: fine)').matches
                    && window.matchMedia('(hover: hover)').matches;
                document.querySelectorAll('form[data-submit-label]').forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (form.dataset.submitting === 'true') {
                            event.preventDefault();
                            return;
                        }

                        var button = form.querySelector('button[type="submit"], button:not([type])');
                        if (!button) return;

                        form.dataset.submitting = 'true';
                        button.dataset.originalLabel = button.innerHTML;
                        button.textContent = form.dataset.submitLabel;
                        button.disabled = true;
                        button.setAttribute('aria-disabled', 'true');
                    });
                });

                var firstError = document.querySelector('[role="alert"]');
                if (firstError) {
                    firstError.setAttribute('tabindex', '-1');
                    firstError.scrollIntoView({ behavior: canUseEnhancedMotion ? 'smooth' : 'auto', block: 'center' });
                    firstError.focus({ preventScroll: true });
                }

                function revealWowContent() {
                    document.querySelectorAll('.wow').forEach(function (element) {
                        element.style.visibility = 'visible';
                        element.style.opacity = '1';
                    });
                }

                if (canUseEnhancedMotion) {
                    setTimeout(revealWowContent, 1500);
                } else {
                    revealWowContent();
                }
            });
        </script>
        @stack('scripts')
        @stack('conversion')
        {!! data_get($infor, 'code_footer') !!}
    </body>
</html>
