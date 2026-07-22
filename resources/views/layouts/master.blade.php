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
    // A-4: fallback ảnh OG dùng logo site (chuẩn hóa URL tuyệt đối giống favicon).
    $defaultOgImage = data_get($siteInfo, 'logo', 'app/assets/images/others/thumb-16.jpg');
    $defaultOgImageUrl = \Illuminate\Support\Str::startsWith($defaultOgImage, ['http://', 'https://', '//'])
        ? $defaultOgImage
        : asset(ltrim($defaultOgImage, '/'));
@endphp
<!DOCTYPE html>
<html lang="vi">
    <!--<< Header Area >>-->
    <head>
        <!-- ========== Meta Tags ========== -->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="@yield('meta_description', $seoDescription)">
        <!-- ======== Page title ============ -->
        <title>@yield('page_title', $siteName)</title>
        <!-- ========== Open Graph ========== -->
        <meta property="og:type" content="website">
        <meta property="og:title" content="@yield('og_title', $siteName)">
        <meta property="og:description" content="@yield('og_description', $seoDescription)">
        <meta property="og:image" content="@yield('og_image', $defaultOgImageUrl)">
        <meta property="og:url" content="{{ url()->current() }}">
        <!--<< Favcion >>-->
        <link rel="shortcut icon" href="{{ $faviconUrl }}" type="image/x-icon" />
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
        <link rel="stylesheet" href="{{ asset('site/assets/css/main.css') }}">
        <link rel="stylesheet" href="{{ asset('site/assets/css/custom.css') }}">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    </head>
    <body>

        @include('layouts.header')
        <div id="smooth-wrapper">
            <div id="smooth-content">
                @yield('content')
                @include('layouts.footer')
            </div>
        </div>

       

        
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
        <script src="{{ asset('site/assets/js/main.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
            @if(session('success'))
                toastr.success(@json(session('success')));
            @endif
            @if(session('error'))
                toastr.error(@json(session('error')));
            @endif
        </script>
        {{-- Slot cho các view push script phụ thuộc jQuery/main.js đã nạp xong. --}}
        @stack('scripts')
    </body>
</html>
