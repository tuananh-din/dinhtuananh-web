(function($) {
    "use strict";
  
    const $documentOn = $(document);
    const canMatchMedia = typeof window.matchMedia === 'function';
    const prefersReducedMotion = canMatchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const hasFinePointer = canMatchMedia && window.matchMedia('(pointer: fine)').matches;
    const canHover = canMatchMedia && window.matchMedia('(hover: hover)').matches;
    const canUseEnhancedMotion = canMatchMedia && !prefersReducedMotion && hasFinePointer && canHover;
    const canUseGsapMotion = canUseEnhancedMotion
        && typeof window.gsap !== 'undefined'
        && typeof window.ScrollTrigger !== 'undefined'
        && typeof window.ScrollSmoother !== 'undefined'
        && typeof window.ScrollToPlugin !== 'undefined'
        && typeof window.SplitText !== 'undefined'
        && typeof window.chroma !== 'undefined';
  
    $documentOn.ready( function() {
  
      /* ================================
       Mobile Menu Js Start
    ================================ */
    
      if (typeof $.fn.meanmenu === 'function') {
        if ($('#mobile-menu').length) {
          $('#mobile-menu').meanmenu({
            meanMenuContainer: '.mobile-menu',
            meanScreenWidth: "1199",
            meanExpand: ['<i class="far fa-plus"></i>'],
          });
        }

        if ($('#mobile-menus').length) {
          $('#mobile-menus').meanmenu({
            meanMenuContainer: '.mobile-menus',
            // Chỉ dùng phiên bản mean (dropdown) ở mobile (<=991px);
            // từ 992px trở lên hiển thị menu ngang gốc trong header.
            meanScreenWidth: "991",
            meanExpand: ['<i class="far fa-plus"></i>'],
          });
        }
      }

     $documentOn.on("click", ".mean-expand", function () {
        let icon = $(this).find("i");

        if (icon.hasClass("fa-plus")) {
            icon.removeClass("fa-plus").addClass("fa-minus"); 
        } else {
            icon.removeClass("fa-minus").addClass("fa-plus"); 
        }
    });

    /* ================================
        Sidebar Toggle & Sticky Item Logic
        ================================ */

        // Open offcanvas
        $(".sidebar__toggle").on("click", function () {
        $(".offcanvas__info").addClass("info-open");
        $(".offcanvas__overlay").addClass("overlay-open");
        $(this).attr("aria-expanded", "true");
        $(".offcanvas__info").attr("aria-hidden", "false");

        // Hide sticky item
        $(".sidebar-sticky-item").fadeOut().removeClass("active");
        });

        // Khi menu mở, đưa focus vào nút đóng để người dùng bàn phím biết vị trí.
        $(".sidebar__toggle").on("click", function () {
            window.setTimeout(function () {
                $(".offcanvas__close button").trigger("focus");
            }, 0);
        });

        // Close offcanvas
        $(".offcanvas__close, .offcanvas__overlay").on("click", function () {
        $(".offcanvas__info").removeClass("info-open");
        $(".offcanvas__overlay").removeClass("overlay-open");
        $(".sidebar__toggle").attr("aria-expanded", "false").trigger("focus");
        $(".offcanvas__info").attr("aria-hidden", "true");

        // Show sticky item
        $(".sidebar-sticky-item").fadeIn().addClass("active");
        });

        // Menu mobile phải luôn có đường thoát bằng bàn phím.
        $(document).on("keydown", function (event) {
            if (event.key === "Escape" && $(".offcanvas__info").hasClass("info-open")) {
                $(".offcanvas__close button").trigger("click");
            }

            if (event.key !== "Tab" || !$(".offcanvas__info").hasClass("info-open")) return;

            var $focusable = $(".offcanvas__info").find('a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled])').filter(':visible');
            if (!$focusable.length) return;

            var first = $focusable.first()[0];
            var last = $focusable.last()[0];
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        });

        /* ================================
        Body Overlay Js Start
        ================================ */

        $(".body-overlay").on("click", function () {
        $(".offcanvas__area").removeClass("offcanvas-opened");
        $(".df-search-area").removeClass("opened");
        $(".body-overlay").removeClass("opened");

        // Show sticky item when overlay clicked
        $(".sidebar-sticky-item").fadeIn().addClass("active");
        });

        /* ================================
        Offcanvas Link Click (Optional)
        ================================ */

        $(".offcanvas a").on("click", function () {
        $(".sidebar-sticky-item").fadeIn().addClass("active");
    });

    
      /* ================================
       Sticky Header Js Start
    ================================ */

      const $stickyHeader = $('#header-sticky');
      const $backToTop = $('#back-top');
      let scrollFrame = null;
      let isSticky = null;
      let isBackToTopVisible = null;

      function updateScrollState() {
        scrollFrame = null;

        const scrollTop = window.pageYOffset || document.documentElement.scrollTop || 0;
        const nextStickyState = scrollTop > 250;
        const viewportBottom = scrollTop + window.innerHeight;
        const documentHeight = Math.max(
          document.documentElement.scrollHeight,
          document.body ? document.body.scrollHeight : 0
        );
        const nextBackToTopState = viewportBottom >= documentHeight - 10;

        if ($stickyHeader.length && nextStickyState !== isSticky) {
          $stickyHeader.toggleClass('sticky', nextStickyState);
          isSticky = nextStickyState;
        }

        if ($backToTop.length && nextBackToTopState !== isBackToTopVisible) {
          $backToTop.toggleClass('show', nextBackToTopState);
          isBackToTopVisible = nextBackToTopState;
        }
      }

      function requestScrollStateUpdate() {
        if (scrollFrame !== null) return;
        const schedule = window.requestAnimationFrame || function (callback) {
          return window.setTimeout(callback, 16);
        };
        scrollFrame = schedule(updateScrollState);
      }

      if ($stickyHeader.length || $backToTop.length) {
        window.addEventListener('scroll', requestScrollStateUpdate, { passive: true });
        window.addEventListener('resize', requestScrollStateUpdate);
        requestScrollStateUpdate();
      }

      /* Legacy static pages still use Magnific for video links. */
      if ($('.video-popup').length && typeof $.fn.magnificPopup === 'function') {
        $(".video-popup").magnificPopup({
          type: "iframe",
          callbacks: {},
        });
      }

      /* ================================
       Counterup Js Start
    ================================ */

      if ($('.count').length && typeof $.fn.counterUp === 'function') {
        $(".count").counterUp({
          delay: 15,
          time: 4000,
        });
      }
  
      /* ================================
       Wow Animation Js Start
    ================================ */

      if ($('.wow').length) {
        if (canUseEnhancedMotion && typeof window.WOW === 'function') {
          new WOW().init();
        } else {
          $('.wow').css({ visibility: 'visible', opacity: 1 });
        }
      }
  
      /* ================================
       Nice Select Js Start
    ================================ */

    if ($('.single-select').length && typeof $.fn.niceSelect === 'function') {
        $('.single-select').niceSelect();
    }

     // portfolio-slide-4
    if (typeof window.Swiper === 'function' && document.querySelectorAll(".gt-vertical-portfolio").length > 0) {
    const interleaveOffset = 0.75;
    var gtVerticalPortfolioSlider = new Swiper('.gt-vertical-portfolio-slider', {
        loop: true,
        direction: "vertical",
        autoplay: false,
        speed: 2000,
        watchSlidesProgress: true,
        mousewheelControl: true,
        mousewheel: true,
        navigation: {
        prevEl: ".array-prev",
        nextEl: ".array-next",
        },
        pagination: {
        el: ".gt-vertical-portfolio-pagination",
        clickable: true,
        },
        on: {
        progress: function () {
          let swiper = this;

          for (let i = 0; i < swiper.slides.length; i++) {
            let slideProgress = swiper.slides[i].progress;
            let innerOffset = swiper.height * interleaveOffset;
            let innerTranslate = slideProgress * innerOffset;

            if (canUseEnhancedMotion && typeof window.TweenMax !== 'undefined') {
              TweenMax.set(swiper.slides[i].querySelector(".slide-inner"), {
                y: innerTranslate,
              });
            }
          }
        },
        setTransition: function (slider, speed) {
            let swiper = this;
            for (let i = 0; i < swiper.slides.length; i++) {
            swiper.slides[i].style.transition = speed + "ms";
            swiper.slides[i].querySelector(".slide-inner").style.transition =
                speed + "ms";
            }
        }
        }
    });
    }

    if (typeof window.Swiper === 'function' && document.querySelectorAll(".gt-horizontal-portfolio").length > 0) {
        const interleaveOffset = 0.75;

        var gtHorizontalPortfolioSlider = new Swiper(".gt-horizontal-portfolio-slider", {
            loop: true,
            direction: "horizontal", 
            autoplay: false,
            speed: 2000,
            watchSlidesProgress: true,
            mousewheel: true,
            navigation: {
            prevEl: ".array-prev",
            nextEl: ".array-next",
            },
            on: {
            progress: function () {
                let swiper = this;

                for (let i = 0; i < swiper.slides.length; i++) {
                let slideProgress = swiper.slides[i].progress;
                let innerOffset = swiper.width * interleaveOffset;
                let innerTranslate = slideProgress * innerOffset;

                if (canUseGsapMotion) {
                    gsap.set(swiper.slides[i].querySelector(".slide-inner"), {
                        x: innerTranslate, // 👈 horizontal translate
                    });
                }
                }
            },
            setTransition: function (slider, speed) {
                let swiper = this;
                for (let i = 0; i < swiper.slides.length; i++) {
                swiper.slides[i].style.transition = speed + "ms";
                swiper.slides[i].querySelector(".slide-inner").style.transition =
                    speed + "ms";
                }
            },
            },
        });
    }
    
      // parallax
        if (canUseGsapMotion && document.querySelectorAll(".gt-portfolio-parallax-box-slider").length > 0) {
            const selectAll = (e) => document.querySelectorAll(e);
            gsap.registerPlugin(ScrollTrigger);
            const tracks = selectAll(".gt-portfolio-parallax-box-slider");

            tracks.forEach((track) => {
                let trackWrapper = track.querySelectorAll(".gt-parallax-slider");
                let allImgs = track.querySelectorAll(".image");

                let trackWrapperWidth = () => {
                    let width = 0;
                    trackWrapper.forEach((el) => (width += el.offsetWidth));
                    return width;
                };

                gsap.defaults({ ease: "none" });
                const gap = window.innerWidth * 0.05;

                let scrollTween = gsap.to(trackWrapper, {
                    x: () => -trackWrapperWidth() + window.innerWidth + gap,
                    scrollTrigger: {
                        trigger: track,
                        pin: true,
                        scrub: 3,
                        start: "center center",
                        end: () => "+=" + (track.scrollWidth - window.innerWidth),
                        onRefresh: (self) => self.getTween().resetTo("totalProgress", 0),
                        invalidateOnRefresh: true
                    }
                });

                allImgs.forEach((img) => {
                    gsap.fromTo(img, { transform: "translateX(-10vw)" }, {
                        transform: "translateX(5vw)",
                        scrollTrigger: {
                            trigger: img.parentNode,
                            containerAnimation: scrollTween,
                            start: "left right",
                            end: "right left",
                            scrub: true,
                        },
                    });
                });
            });
        }

      /* ================================
       Parallaxie Js Start
    ================================ */

        if ($('.parallaxie').length && typeof $.fn.parallaxie === 'function' && $(window).width() > 991) {
            if ($(window).width() > 768) {
                $('.parallaxie').parallaxie({
                    speed: 0.55,
                    offset: 0,
                });
            }
        }

        
       /* ================================
      Testimonial Slider Js Start
    ================================ */

   if ($('.testimonial-slider').length > 0 && typeof window.Swiper === 'function') {
    const testimonialSlider = new Swiper(".testimonial-slider", {
        spaceBetween: 30,
        speed: 1300,
        loop: true,
        autoplay: {
            delay: 2000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: ".array-next",
            prevEl: ".array-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            type: "fraction", 
        },
        breakpoints: {
            991: {
                slidesPerView: 2,
            },
            767: {
                slidesPerView: 1.6,
            },
            575: {
                slidesPerView: 1.1,
            },
            0: {
                slidesPerView: 1.1,
            },
        },
    });
    }

    
    /* ================================
      Project Slider Js Start
    ================================ */

    if($('.project-slider-555').length > 0 && typeof window.Swiper === 'function') {
        const projectSlider555 = new Swiper(".project-slider-555", {
            spaceBetween: 30,
            speed: 1300,
            loop: true,
            //centeredSlides: true,
            autoplay: {
                delay: 2000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".array-prev",
                prevEl: ".array-next",
            },
            breakpoints: {
               
                991: {
                    slidesPerView: 2,
                },
                767: {
                    slidesPerView: 1.6,
                },
                575: {
                    slidesPerView: 1.1,
                },
                0: {
                    slidesPerView: 1.1,
                },
            },
        });
    }


    if($('.testimonial-slider-2').length > 0 && typeof window.Swiper === 'function') {
        const testimonialSlider2 = new Swiper(".testimonial-slider-2", {
            spaceBetween: 30,
            speed: 1300,
            loop: true,
            //centeredSlides: true,
            autoplay: {
                delay: 2000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".array-prev2",
                prevEl: ".array-next2",
            },
            breakpoints: {
               
                1199: {
                    slidesPerView: 3,
                },
                 991: {
                    slidesPerView: 2,
                },
                767: {
                    slidesPerView: 2.1,
                },
                575: {
                    slidesPerView: 1.4,
                },
                0: {
                    slidesPerView: 1.2,
                },
            },
        });
    }

    /* ================================
      Brand Slider Js Start
    ================================ */

    if($('.brand-slider-2').length > 0 && typeof window.Swiper === 'function') {
        const brandSlider2 = new Swiper(".brand-slider-2", {
            spaceBetween: 30,
            speed: 1300,
            loop: true,
            autoplay: {
                delay: 2000,
                disableOnInteraction: false,
            },
            breakpoints: {
               1399: {
                    slidesPerView: 6,
                },
               
                1199: {
                    slidesPerView: 5.7,
                },
                 991: {
                    slidesPerView: 4.7,
                },
                767: {
                    slidesPerView: 3.7,
                },
                575: {
                    slidesPerView: 2.8,
                },
                400: {
                    slidesPerView: 2,
                },
                0: {
                    slidesPerView: 1.8,
                },
            },
        });
    }

    if($('.brand-slider-3').length > 0 && typeof window.Swiper === 'function') {
        const brandSlider3 = new Swiper(".brand-slider-3", {
            spaceBetween: 30,
            speed: 1300,
            loop: true,
            autoplay: {
                delay: 2000,
                disableOnInteraction: false,
            },
            breakpoints: {
               
                991: {
                    slidesPerView: 4,
                },
                767: {
                    slidesPerView: 3.4,
                },
                575: {
                    slidesPerView: 2.5,
                },
                0: {
                    slidesPerView: 2,
                },
            },
        });
    }

     if($('.box-slider').length > 0 && typeof window.Swiper === 'function') {
        const BoxSlider = new Swiper(".box-slider", {
            spaceBetween: 30,
            speed: 1300,
            loop: true,
            autoplay: {
                delay: 2000,
                disableOnInteraction: false,
            },
            breakpoints: {
               
                991: {
                    slidesPerView: 1,
                },
                767: {
                    slidesPerView: 1,
                },
                575: {
                    slidesPerView: 1,
                },
                0: {
                    slidesPerView: 1,
                },
            },
        });
    }

    


    
    /* ================================
       Testimonial SLider Js Start
    ================================ */

    if ($('.testimonial-slider-3').length && typeof window.Swiper === 'function') {
    var testimonialSlider3 = new Swiper(".testimonial-slider-3", {
      slidesPerView: 1,
      spaceBetween: 24,
      centeredSlides: true,
      grabCursor: true,
      loop: true,
      speed: 1000,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      effect: "cube",
      cubeEffect: {
        shadow: true,
        slideShadows: true,
        shadowOffset: 20,
        shadowScale: 0.94,
      },
      navigation: {
        nextEl: ".array-next",
        prevEl: ".array-prev",
      },
    });
    }

    /* ================================
       Project Inner Slider Js Start
    ================================ */

    if($('.project-inner-slider').length > 0 && typeof window.Swiper === 'function') {
        const projectInnerSlider = new Swiper(".project-inner-slider", {
            spaceBetween: 30,
            speed: 1300,
            loop: true,
             centeredSlides: true,
            autoplay: {
                delay: 2000,
                disableOnInteraction: false,
            },
            breakpoints: {
                1199: {
                    slidesPerView: 3.1,
                },

                991: {
                    slidesPerView: 2.7,
                },

                767: {
                    slidesPerView: 2.5,
                },
                575: {
                    slidesPerView: 1.6,
                },
                0: {
                    slidesPerView: 1.3,
                },
            },
        });
    }

    /* ================================
       Reela Slider Js Start
    ================================ */
     if($('.reels-slider').length > 0 && typeof window.Swiper === 'function') {
        const ReelslSlider = new Swiper(".reels-slider", {
            spaceBetween: 30,
            speed: 1300,
            centeredSlides: true,
            loop: true,
            autoplay: {
                delay: 2000,
                 disableOnInteraction: false,
            },
        
            breakpoints: {
               
                1199: {
                    slidesPerView: 6,
                },
                 991: {
                    slidesPerView: 4.1,
                },
                767: {
                    slidesPerView: 3.1,
                },
                575: {
                    slidesPerView: 1.2,
                },
                0: {
                    slidesPerView: 1.3,
                },
            },
        });
    }

    /* ================================
       Test Slider Js Start
    ================================ */
     if($('.test-slider').length > 0 && typeof window.Swiper === 'function') {
        const TestlSlider = new Swiper(".test-slider", {
            spaceBetween: 30,
            speed: 1300,
            loop: true,
            autoplay: {
                delay: 2000,
                 disableOnInteraction: false,
            },
            navigation: {
                    nextEl: ".array-prev",
                    prevEl: ".array-next",
                },
        
            breakpoints: {
               
                1199: {
                    slidesPerView: 1,
                },
                 991: {
                    slidesPerView: 1,
                },
                767: {
                    slidesPerView: 1,
                },
                575: {
                    slidesPerView: 1,
                },
                0: {
                    slidesPerView: 1,
                },
            },
        });
    }
  
    /* ================================
       Counter Progress Js Start
    ================================ */

    document.querySelectorAll('.skill-box-items-2').forEach((skill) => {
    const progressBar = skill.querySelector('.progress-bar');
    const countEl = skill.querySelector('.count');
    if (!progressBar || !countEl) return;
    const target = parseInt(countEl.textContent, 10);
    if (Number.isNaN(target)) return;

    if (!canUseEnhancedMotion) {
        countEl.textContent = target;
        progressBar.style.width = `${target}%`;
        return;
    }
    const duration = 2600; // 2.6s

    // Reset
    countEl.textContent = '0';
    progressBar.style.width = '0%';

    let start = null;

    function animate(timestamp) {
        if (!start) start = timestamp;
        const progress = timestamp - start;
        const percentage = Math.min(progress / duration, 1);

        const current = Math.floor(target * percentage);

        // Update UI
        countEl.textContent = current;
        progressBar.style.width = `${current}%`;

        if (progress < duration) {
            requestAnimationFrame(animate);
        } else {
            countEl.textContent = target;       // Ensure exact final value
            progressBar.style.width = `${target}%`;
        }
    }

    requestAnimationFrame(animate);
    });

    /* ================================
        Mouse Cursor Animation Js Start
    ================================ */

    if (canUseEnhancedMotion && $(".mouseCursor").length > 0) {
        function itCursor() {
            var myCursor = jQuery(".mouseCursor");
            if (myCursor.length) {
                if ($("body")) {
                    const e = document.querySelector(".cursor-inner"),
                        t = document.querySelector(".cursor-outer");
                    let n, i = 0, o = !1;
                    window.onmousemove = function(s) {
                        if (!o) {
                            t.style.transform = "translate(" + s.clientX + "px, " + s.clientY + "px)";
                        }
                        e.style.transform = "translate(" + s.clientX + "px, " + s.clientY + "px)";
                        n = s.clientY;
                        i = s.clientX;
                    };
                    $("body").on("mouseenter", "button, a, .cursor-pointer", function() {
                        e.classList.add("cursor-hover");
                        t.classList.add("cursor-hover");
                    });
                    $("body").on("mouseleave", "button, a, .cursor-pointer", function() {
                        if (!($(this).is("a", "button") && $(this).closest(".cursor-pointer").length)) {
                            e.classList.remove("cursor-hover");
                            t.classList.remove("cursor-hover");
                        }
                    });
                    e.style.visibility = "visible";
                    t.style.visibility = "visible";
                }
            }
        }
        itCursor();
    }

    /* ================================
        Back To Top Button Js Start
    ================================ */
    $documentOn.on('click', '#back-top', function() {
        window.scrollTo({
            top: 0,
            behavior: canUseEnhancedMotion ? 'smooth' : 'auto',
        });
        return false;
    });
	
    /* ================================
       Smooth Scroller And Title Animation Js Start
    ================================ */

    if (canUseGsapMotion) {
	const hasSmoothScrollShell = $('#smooth-wrapper').length && $('#smooth-content').length;
    if (hasSmoothScrollShell) {
    gsap.registerPlugin(ScrollTrigger, ScrollSmoother, SplitText, ScrollToPlugin);

    gsap.config({
        nullTargetWarn: false,
    });
    }

    // Trang doc bai dung native scroll de position: sticky cua Muc luc hoat dong.
    if (hasSmoothScrollShell && !document.body.classList.contains('is-article') && !document.body.classList.contains('is-dpm')) {
    // Initialize ScrollSmoother
    let smoother = ScrollSmoother.create({
        wrapper: "#smooth-wrapper",
        content: "#smooth-content",
        smooth: 2,
        effects: true,
        smoothTouch: 0.1,
        normalizeScroll: false,
        ignoreMobileResize: true,
    });

    // After smoother initialized, run SplitText animations
    if ($(".tv_hero_title").length) {
        $(".tv_hero_title").each(function () {
        let $el = $(this);
        let split = new SplitText($el, {
            type: "lines,words,chars",
            linesClass: "split-line"
        });

        gsap.set($el, { perspective: 400 });

        if ($el.hasClass("hero_title_1")) {
            gsap.set(split.chars, { x: 100, opacity: 0 });
        }
        if ($el.hasClass("hero_title_2")) {
            gsap.set(split.chars, { y: 100, opacity: 0 });
        }
        if ($el.hasClass("hero_title_3")) {
            gsap.set(split.chars, {
            y: 100,
            scaleY: 0,
            opacity: 0,
            rotationX: 15
            });
        }

        // IMPORTANT: Use smoother effects
        gsap.to(split.chars, {
            scrollTrigger: {
            trigger: $el,
            start: "top 90%",
            scroller: smoother.scrollContainer, // 👈 this line is the key fix
            toggleActions: "play reverse play reverse",
            markers: false,
            },
            x: 0,
            y: 0,
            scaleX: 1,
            scaleY: 1,
            opacity: 1,
            duration: 1,
            stagger: 0.05,
            rotationX: 15,
            delay: 0.1,
            ease: "power3.inOut"
        });
        });
    }

    

    // Update ScrollTrigger when smoother refreshes
    ScrollTrigger.addEventListener("refresh", () => smoother.refresh());
    }

    /* ================================
       Sticky Js Start
    ================================ */

    let pr = gsap.matchMedia();
	pr.add("(min-width: 1199px)", () => {
		let tl = gsap.timeline();
		let panels = document.querySelectorAll('.tp-panel-pin')
		panels.forEach((section, index) => {
			tl.to(section, {
				scrollTrigger: {
					trigger: section,
					pin: section,
					scrub: 1,
					start: 'top top',
					end: "bottom 99%",
					endTrigger: '.tp-panel-pin-area',
					pinSpacing: false,
					markers: false,
				},
			})
		})
	});

     
    

    /* ================================
       Project Anim Js Start
    ================================ */

	if ($('.tp-project-5-2-area').length > 0) {
		let project_text = gsap.timeline({
			scrollTrigger: {
				trigger: ".tp-project-5-2-area",
				start: 'top center-=350',
				end: "bottom 50%",
				pin: ".tp-project-5-2-title",
				markers: false,
				pinSpacing: false,
				scrub: 1,
			}
		})
		project_text.set(".tp-project-5-2-title", {
			scale: .6,
			duration: 2
		})
		project_text.to(".tp-project-5-2-title", {
			scale: 1,
			duration: 2
		})
		project_text.to(".tp-project-5-2-title", {
			scale: 1,
			duration: 2
		}, "+=2")

         project_text.to(".tp-project-5-2-title", {
            autoAlpha: 0,
            duration: 2
        });
	}

    /* ================================
       Reveal Img Js Start
    ================================ */

    const revealItems = document.querySelectorAll('.reveal-img');

    if (revealItems.length) {

        // Set initial state: hidden and slightly left
        gsap.set(revealItems, { x: -100, opacity: 0 });

        revealItems.forEach((item) => {
            gsap.to(item, {
                x: 0,        // move to original position
                opacity: 1,  // fade in
                duration: 1.2,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: item,
                    start: 'top 60%',
                    end: 'bottom 20%', // optional, can adjust
                    toggleActions: 'play reverse play reverse', // animate in/out
                }
            });
        });
    }

    /* ================================
       Title SplitText Js Start
    ================================ */
   
    if ($('.wt-about-title').length > 0) {

        let cta = gsap.timeline({
            repeat: -1,
            delay: 0.5,
            scrollTrigger: {
                trigger: '.wt-about-title',
                start: 'bottom 100%-=50px'
            }
        });
        gsap.set('.wt-about-title', {
            opacity: 0
        });
        gsap.to('.wt-about-title', {
            opacity: 1,
            duration: 1,
            ease: 'power1.out',
            scrollTrigger: {
                trigger: '.wt-about-title',
                start: 'bottom 100%-=50px',
                once: true
            }
        });

        let mySplitText = new SplitText(".wt-about-title", {
            type: "words,chars"
        });
        let chars = mySplitText.chars;
        let endGradient = chroma.scale(['#888', '#888', '#888', '#888', '#888']);
        cta.to(chars, {
            duration: 0.5,
            scaleY: 0.6,
            ease: "power1.out",
            stagger: 0.04,
            transformOrigin: 'center bottom'
        });
        cta.to(chars, {
            yPercent: -20,
            ease: "elastic",
            stagger: 0.03,
            duration: 0.8
        }, 0.5);
        cta.to(chars, {
            scaleY: 1,
            ease: "elastic.out",
            stagger: 0.03,
            duration: 1.5
        }, 0.5);
        cta.to(chars, {
            color: (i, el, arr) => {
                return endGradient(i / arr.length).hex();
            },
            ease: "power1.out",
            stagger: 0.03,
            duration: 0.3
        }, 0.5);
        cta.to(chars, {
            yPercent: 0,
            ease: "back",
            stagger: 0.03,
            duration: 0.8
        }, 0.7);
        cta.to(chars, {
            color: '#888',
            duration: 1.4,
            stagger: 0.05
        });
    }

    if ($('.wt-about-title2').length > 0) {

        let cta = gsap.timeline({
            repeat: -1,
            delay: 0.5,
            scrollTrigger: {
                trigger: '.wt-about-title2',
                start: 'bottom 100%-=50px'
            }
        });

        // Initial style
        gsap.set('.wt-about-title2', {
            opacity: 0,
            color: '#BFF747',
            // textDecoration removed
        });

        // Fade in once on scroll
        gsap.to('.wt-about-title2', {
            opacity: 1,
            duration: 1,
            ease: 'power1.out',
            scrollTrigger: {
                trigger: '.wt-about-title2',
                start: 'bottom 100%-=50px',
                once: true
            }
        });

        // Split text into words & characters
        let mySplitText = new SplitText(".wt-about-title2", {
            type: "words,chars"
        });
        let chars = mySplitText.chars;

        // Animation
        let endGradient = chroma.scale(['#BFF747', '#BFF747', '#BFF747', '#BFF747', '#BFF747']);
        cta.to(chars, {
            duration: 0.5,
            scaleY: 0.6,
            ease: "power1.out",
            stagger: 0.04,
            transformOrigin: 'center bottom'
        });
        cta.to(chars, {
            yPercent: -20,
            ease: "elastic",
            stagger: 0.03,
            duration: 0.8
        }, 0.5);
        cta.to(chars, {
            scaleY: 1,
            ease: "elastic.out",
            stagger: 0.03,
            duration: 1.5
        }, 0.5);
        cta.to(chars, {
            color: (i, el, arr) => {
                return endGradient(i / arr.length).hex();
            },
            ease: "power1.out",
            stagger: 0.03,
            duration: 0.3
        }, 0.5);
        cta.to(chars, {
            yPercent: 0,
            ease: "back",
            stagger: 0.03,
            duration: 0.8
        }, 0.7);
        cta.to(chars, {
            color: '#BFF747',
            duration: 1.4,
            stagger: 0.05
        });
    }

     /* ================================
       Funfact Panel Js Start
    ================================ */

    if ($('.tp-funfact-panel-wrap').length) {
        let mm = gsap.matchMedia();

        mm.add("(min-width: 1200px)", () => {
            let sections = gsap.utils.toArray(".tp-funfact-panel");

            gsap.to(sections, {
                xPercent: -100 * (sections.length - 1),
                ease: "none",
                scrollTrigger: {
                    start: "top 120px",
                    trigger: ".tp-funfact-panel-wrap",
                    pin: true,
                    scrub: 1,
                    end: () => "+=" + document.querySelector(".tp-funfact-panel-wrap").offsetWidth
                }
            });
        });
    }



    /* ================================
      Choose Anim Js Start
    ================================ */

    gsap.registerPlugin(ScrollTrigger);

    if (document.querySelectorAll('.design-choose-item-wrap').length) {
        const pw = ScrollTrigger.matchMedia();
        pw.add("(min-width: 1200px)", () => {
            document.querySelectorAll('.design-choose-item-wrap').forEach(item => {
                gsap.set(item.querySelector('.design-choose-item-1'), { x: -400, rotate: -40 });
                gsap.set(item.querySelector('.design-choose-item-2'), { x: 400, rotate: 40 });

                let tl = gsap.timeline({
                    scrollTrigger: {
                        trigger: item,
                        start: 'top 120%',
                        end: 'top 20%',
                        scrub: 1,
                    }
                });

                tl.to(item.querySelector('.design-choose-item-1'), { x: 0, rotate: 0 })
                .to(item.querySelector('.design-choose-item-2'), { x: 0, rotate: 0 }, 0);
            });
        });
    }

    /* ================================
       Bottom To Top Anim Js Start
    ================================ */

    gsap.utils.toArray(' .top_view').forEach((el, index) => {
      let tlcta = gsap.timeline({
        scrollTrigger: {
          trigger: el,
          scrub: 1.5,
          end: "top 40%",
          start: "top 100%",
          toggleActions: "play none none reverse",
          markers: false
        }
      })

      tlcta
      .set(el, {transformOrigin: 'center center'})
      .from(el, { opacity: 1, scale: 1, yPercent: "50"}, {opacity: 1, yPercent: 0, duration: 1, immediateRender: false})
    });


 
    // Gallery scroll
       gsap.registerPlugin(ScrollTrigger);

        if (document.querySelector(".gallery")) {
        const pr = ScrollTrigger.matchMedia();

        pr.add("(min-width: 1199px)", () => {
            // সব gallery আইটেম নেব
            const galleries = document.querySelectorAll(".gallery");
            const wrapper = document.querySelector(".gallery-wrapper");

            if (!galleries.length || !wrapper) return;

            // প্রতিটা gallery pin হবে, আর নিচে নামলে fade+scale effect পাবে
            galleries.forEach((gallery, index) => {
            const isLast = index === galleries.length - 1;

            // নিচে চলে যাওয়ার সময় হালকা fade এবং scale কমবে
            gsap.to(gallery, {
                scale: isLast ? 1 : 0.85, // শেষটা full থাকবে
                opacity: isLast ? 1 : 0,
                ease: "none",
                scrollTrigger: {
                trigger: gallery,
                start: "top top",
                end: "bottom 80%",
                scrub: true,
                pin: true,
                pinSpacing: false,
                endTrigger: wrapper,
                markers: false,
                },
            });
            });

            // Cleanup on resize condition change
            return () => {
            ScrollTrigger.getAll().forEach((trigger) => trigger.kill());
            };
        });
        }
        

//         const swiper = new Swiper('.swiper-container', {
//             effect: 'coverflow',
//             centeredSlides: true,
//             slidesPerView: 1,
//             loop: true,
//             speed: 600,
            
//             autoplay: {
//                 delay: 3000,
//             },
            
//             coverflowEffect: {
//                 rotate: 50,
//                 stretch: 0,
//                 depth: 100,
//                 modifier: 1,
//                 slideShadows: false,
//             },
            
//             spaceBetween: 0,
            
//             breakpoints: {
//                 320: {
//                     slidesPerView: 2,
//                 },
//                 560: {
//                     slidesPerView: 3,
//                 },
//                 990: {
//                     slidesPerView: 4,
//                 }
//             },

//             pagination: {
//             el: ".dot-number",
//             clickable: true,
//             renderBullet: function(index, className) {
//                 const dotContent = document.querySelectorAll(
//                     ".dot-number .dot-num"
//                 );
//                 return `
//             <span class="${className}">
//                 ${dotContent[index]?.outerHTML || ""}
//             </span>
//         `;
//             },
//         },

//             navigation: {
//                 nextEl: ".array-prev",
//                 prevEl: ".array-next",
//             },
//         });

//         document.querySelectorAll('.coverflow-slider-text-active').forEach((el) => {
//     new Swiper(el, {
//         direction: 'vertical',   // vertical scroll
//         slidesPerView: 1,
//         spaceBetween: 30,
//         loop: true,
//         speed: 1500,
//         allowTouchMove: true,    // চাইলে false করতে পারো
//         mousewheel: true,        // scroll দিয়েও পরিবর্তন করা যাবে
//         autoplay: {
//             delay: 2500,
//             disableOnInteraction: false,
//         },
//     });
// });


    }

// === IMAGE SLIDER ===
if (typeof window.Swiper === 'function'
    && document.querySelector('.coverflow-slider-active')
    && document.querySelector('.coverflow-slider-text-active')) {
const coverflow_slider = new Swiper('.coverflow-slider-active', {
    effect: 'coverflow',
    centeredSlides: true,
    slidesPerView: 1,
    loop: true,
    speed: 800,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: false,
    },
    spaceBetween: 0,
    breakpoints: {
        320: {
            slidesPerView: 2,
        },
        560: {
            slidesPerView: 3,
        },
        990: {
            slidesPerView: 4,
        }
    },
    pagination: {
        el: ".dot-number",
        clickable: true,
        renderBullet: function(index, className) {
            const dotContent = document.querySelectorAll(".dot-number .dot-num");
            return `
                <span class="${className}">
                    ${dotContent[index]?.outerHTML || ""}
                </span>
            `;
        },
    },
    navigation: {
        nextEl: ".array-next",
        prevEl: ".array-prev",
    },
});

// === TEXT SLIDER ===
const text_slider = new Swiper('.coverflow-slider-text-active', {
    direction: 'vertical',
    slidesPerView: 1,
    spaceBetween: 0,
    loop: true,
    speed: 800,
    allowTouchMove: false,
    navigation: {
    nextEl: ".array-next", // 🔥 next button
    prevEl: ".array-prev", // 🔥 prev button
  },
});

// === SYNC BOTH ===
coverflow_slider.on('slideChangeTransitionStart', function () {
    text_slider.slideToLoop(coverflow_slider.realIndex);
});

text_slider.on('slideChangeTransitionStart', function () {
    coverflow_slider.slideToLoop(text_slider.realIndex);
});
}


    if (canUseGsapMotion) {

 

        
     /* ================================
       Service Panel Js Start
    ================================ */

	let sv = gsap.matchMedia();
	sv.add("(min-width: 1199px)", () => {
		let tl = gsap.timeline();
		let projectpanels = document.querySelectorAll('.tp-service-panel');
		let baseOffset = 150;
		let offsetIncrement = 120;

		projectpanels.forEach((section, index) => {
			let topOffset = baseOffset + (index * offsetIncrement);
			tl.to(section, {
				scrollTrigger: {
					trigger: section,
					pin: section,
					scrub: 1,
					start: `top ${topOffset}px`,
					end: "bottom 50%",
					endTrigger: '.tp-service-pin',
					pinSpacing: false,
					markers: false,
				},
			});
		});
	});

    let sv2 = gsap.matchMedia();
	sv2.add("(min-width: 1199px)", () => {
		let tl = gsap.timeline();
		let projectpanels = document.querySelectorAll('.tp-service-panel2');
		let baseOffset = 150;
		let offsetIncrement = 120;

		projectpanels.forEach((section, index) => {
			let topOffset = baseOffset + (index * offsetIncrement);
			tl.to(section, {
				scrollTrigger: {
					trigger: section,
					pin: section,
					scrub: 1,
					start: `top ${topOffset}px`,
					end: "bottom 55%",
					endTrigger: '.tp-service-pin2',
					pinSpacing: false,
					markers: false,
				},
			});
		});
	});

    /* ================================
       Text Up Js Start
    ================================ */

     // Text Up Scroll 
    if ($('.text-splite-up').length > 0) {
        let splitTitleLines = gsap.utils.toArray(".text-splite-up");
        splitTitleLines.forEach(splitTextLine => {
            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: splitTextLine,
                    start: 'top 90%',
                    end: 'bottom 80%',
                    scrub: 1,
                    markers: false,
                    toggleActions: 'play none none none'
                }
            });

            const itemSplitted = new SplitText(splitTextLine, {
                type: "words, lines"
            });
            gsap.set(splitTextLine, {
                perspective: 400
            });
            itemSplitted.split({
                type: "lines"
            })
            tl.from(itemSplitted.lines, {
                duration: 1,
                delay: 0.3,
                opacity: 0,
                rotationX: -80,
                force3D: true,
                transformOrigin: "top center -50",
                stagger: 0.1
            });
        });
    }

     /* ================================
      Text Invert Js Start
    ================================ */

    const split = new SplitText(".text_invert", { type: "lines" });

    split.lines.forEach((target) => {
        gsap.to(target, {
            backgroundPositionX: 0,
            ease: "none",
            scrollTrigger: {
                trigger: target,
                scrub: 1,
                start: 'top 85%',
                end: "bottom center",
            }
        });
    });

    const split2 = new SplitText(".text_invert-2", { type: "lines" });

    split2.lines.forEach((target) => {
        gsap.to(target, {
            backgroundPositionX: 0,
            ease: "none",
            scrollTrigger: {
                trigger: target,
                scrub: 1,
                start: 'top 85%',
                end: "bottom center",
            }
        });
    });
     
     /* ================================
       Des Portfolio Anim Js Start
    ================================ */
    
    if (document.querySelector(".des-portfolio-wrap")) {
        const pr = ScrollTrigger.matchMedia();

        pr.add("(min-width: 1199px)", () => {

            const sections = document.querySelectorAll(".des-portfolio-panel");
            const wrap = document.querySelector(".des-portfolio-wrap");

            if (!sections.length || !wrap) return;

            // Initial state
            gsap.set(sections, { scale: 1 });

            // Animate each section except the last one
            sections.forEach((section, index) => {
                const isLast = index === sections.length - 1;

                gsap.to(section, {
                    scale: isLast ? 1 : 0.8, // 👈 last one stays full-size
                    ease: "none",
                    scrollTrigger: {
                        trigger: section,
                        start: "top top",
                        end: "bottom 60%",
                        scrub: true,
                        pin: true,
                        pinSpacing: false,
                        endTrigger: wrap,
                        markers: false,
                    },
                });
            });

            // Cleanup on condition change
            return () => {
                ScrollTrigger.getAll().forEach((trigger) => trigger.kill());
            };
        });
    }

    /* ================================
       Approach Anim Js Start
    ================================ */

    if (document.querySelectorAll(".approach-area").length > 0) {

        const boxes = document.querySelectorAll(".approach-area .approach-box");

        gsap.from(boxes, {
        x: "100%",
        duration: 1,
        stagger: 0.3,
        ease: "power2.out",
        scrollTrigger: {
            scrub: 2,
            trigger: ".approach-wrapper-box",
            start: "top 100%",
            end: "bottom 40%",
            toggleActions: "play none none reverse",
        }
        });
    }

    if (document.querySelectorAll(".grow").length > 0) {
    document.querySelectorAll(".grow").forEach((item) => {
        gsap.fromTo(item,
        { x: 200, opacity: 0 }, // right side theke asbe
        {
            x: 0,
            opacity: 1,
            ease: "power2.out",
            scrollTrigger: {
            trigger: item,
            scrub: 2,
            start: "top 90%",
            end: "top 50%",
            }
        }
        );
    });
    }

    if (document.querySelectorAll(".grow2").length > 0) {
        document.querySelectorAll(".grow2").forEach((item) => {
            gsap.fromTo(item,
                { x: -200, opacity: 0 }, // left theke asbe
                {
                    x: 0,
                    opacity: 1,
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: item,
                        scrub: 2,
                        start: "top 90%",
                        end: "top 50%",
                    }
                }
            );
        });
    }
    }

    /* ================================
       Button Active Js Start
    ================================ */

    // Check if .budget-button exists on this page
    if ($('.budget-button').length) {
        $(".budget-button .budget-btn").on("click", function() {
            // Remove active class from all buttons inside this container
            $(".budget-button .budget-btn").removeClass("active");
            // Add active class to the clicked button
            $(this).addClass("active");
        });
    }

   if (canUseGsapMotion && $('.gt-project-area').length > 0) {
	let project_text = gsap.timeline({
		scrollTrigger: {
			trigger: ".gt-project-area",
			start: 'top center-=350',
			end: "bottom 80%",
			pin: ".gt-project-title4",
			markers: false,
			pinSpacing: false,
			scrub: 1,
		}
	});

	project_text.set(".gt-project-title4", {
		scale: 0.6,
		opacity: 1,
		duration: 2
	});

	project_text.to(".gt-project-title4", {
		scale: 1,
		duration: 2
	});

	project_text.to(".gt-project-title4", {
		scale: 1,
		duration: 2
	}, "+=2");

	project_text.to(".gt-project-title4", {
		opacity: 0,
		y: -50,
		duration: 1
	});
}


    
    }); // End Document Ready Function

    

     /* ================================
      Preloader Js Start
    ================================ */

     function preloader() {
        const preloaderElement = document.querySelector('.preloader');
        if (!preloaderElement) return;
        const hidePreloader = () => {
            preloaderElement.classList.add('preloader-hidden');
            preloaderElement.setAttribute('aria-hidden', 'true');
        };
        let seen = false;
        try { seen = sessionStorage.getItem('preloaded') === '1'; } catch (e) {}
        if (seen) {
            hidePreloader();
            return;
        }
        try { sessionStorage.setItem('preloaded', '1'); } catch (e) {}
        document.addEventListener('DOMContentLoaded', () => setTimeout(hidePreloader, 250), { once: true });
        setTimeout(hidePreloader, 800);
  }
  // Init preloader
  preloader();


   /* ================================
      Feature Circle Slider Js Start
    ================================ */

    function feature_work_experience() {

    /* ==============================
        Preview + Main Slider Sync
    ============================== */
    if ($(".fw_preview_slider_active").length && $(".fw_main_slider_active").length && typeof window.Swiper === 'function') {

        const fw_preview_slider = new Swiper(".fw_preview_slider_active", {
        speed: 500,
        slidesPerView: "auto",
        spaceBetween: 20,
        });

        const fw_main_slider = new Swiper(".fw_main_slider_active", {
        speed: 500,
        slidesPerView: "auto",
        effect: "fade",
        fadeEffect: { crossFade: true },
        navigation: {
            nextEl: ".array-prev",
            prevEl: ".array-next",
        },
        thumbs: {
            swiper: fw_preview_slider,
        },
        });
    }

    /* ==============================
        Circular Layout for Preview
    ============================== */
    if ($(".feature-work-experience-preview-slider").length) {

        const $wrapper = document.querySelector(".feature-work-experience-preview-slider .swiper-wrapper");
        if (!$wrapper) return;
        const $slides = $wrapper.querySelectorAll(".swiper-slide");

        const radius = 450; // circle radius
        const centerX = $wrapper.clientWidth / 2;
        const centerY = $wrapper.clientHeight / 2;
        const total = $slides.length;
        const angleStep = (2 * Math.PI) / total;

        // Position slides in circular layout
        $slides.forEach((slide, i) => {
        const angle = i * angleStep;
        const x = centerX + radius * Math.cos(angle) - slide.clientWidth / 2;
        const y = centerY + radius * Math.sin(angle) - slide.clientHeight / 2;

        Object.assign(slide.style, {
            position: "absolute",
            left: `${x}px`,
            top: `${y}px`,
        });
        });

        /* ==============================
            GSAP Scroll Rotation
        ============================== */
        if (canUseGsapMotion) {
            gsap.registerPlugin(ScrollTrigger);

            gsap.to(".feature-work-experience-preview-slider .swiper-wrapper", {
            rotation: -40,
            ease: "none",
            scrollTrigger: {
                trigger: ".feature-work-experience-preview-slider",
                start: "top center",
                end: "bottom top",
                scrub: true,
                toggleActions: "play none none reverse",
            },
            });
        }
    }
    }

    /* =========================================================
        Document Ready
    ========================================================= */
    $(document).ready(function () {
    feature_work_experience();
    });

    /* ================================
       Type Text Js Start
    ================================ */

    (function ($) {
        "use strict";

        

        
    })(jQuery);
    // Type Text Area End

    /* Fallback chung: text animation khong duoc phep bi ket o trang thai an. */
    (function () {
        const selectors = [
            '.text_invert',
            '.text_invert-2',
            '.wt-about-title2',
            '.footer-big-text',
            '.tv_hero_title',
            '#typing-text'
        ];

        function revealText(element) {
            element.style.opacity = '1';
            element.style.visibility = 'visible';
            element.style.transform = 'none';

            if (element.matches('.text_invert, .text_invert-2')) {
                element.querySelectorAll('div').forEach((line) => {
                    line.style.backgroundImage = 'none';
                    line.style.backgroundPositionX = '0';
                    line.style.webkitBackgroundClip = 'border-box';
                    line.style.backgroundClip = 'border-box';
                    line.style.color = 'var(--header)';
                });
            }

            element.querySelectorAll('.split-word, .split-line, .char, .word').forEach((part) => {
                part.style.opacity = '1';
                part.style.visibility = 'visible';
                part.style.transform = 'none';
                part.style.color = 'inherit';
            });
        }

        function scheduleFallback(element) {
            let timer;
            const queueReveal = () => {
                window.clearTimeout(timer);
                timer = window.setTimeout(() => revealText(element), 1500);
            };

            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) return;
                        queueReveal();
                        observer.unobserve(entry.target);
                    });
                }, { threshold: 0.05 });
                observer.observe(element);
            } else {
                queueReveal();
            }

            window.setTimeout(() => revealText(element), 1500);
        }

        document.querySelectorAll(selectors.join(',')).forEach(scheduleFallback);
    })();

    document.addEventListener('click', function (event) {
        const dismissButton = event.target.closest('[data-notice-dismiss]');
        if (!dismissButton) return;

        const notice = dismissButton.closest('[data-notice]');
        if (!notice || notice.dataset.noticeDismissible !== 'true') return;

        notice.hidden = true;
    });

    (function () {
        document.querySelectorAll('.dpm-page .dpm-curriculum').forEach(function (curriculum) {
            const items = Array.from(curriculum.children).filter(function (item) {
                return item.tagName === 'DETAILS';
            });

            items.forEach(function (item) {
                item.addEventListener('toggle', function () {
                    if (!item.open) return;

                    items.forEach(function (otherItem) {
                        if (otherItem !== item) otherItem.open = false;
                    });
                });
            });
        });
    })();

    (function () {
        const dialog = document.getElementById('case-study-image-dialog');
        const triggers = Array.from(document.querySelectorAll('[data-case-study-image-trigger]'));
        if (!dialog || !triggers.length || typeof dialog.showModal !== 'function') return;

        const image = document.getElementById('case-study-image-dialog-image');
        const title = document.getElementById('case-study-image-dialog-title');
        const caption = document.getElementById('case-study-image-dialog-caption');
        const count = document.getElementById('case-study-image-dialog-count');
        const previousButton = dialog.querySelector('[data-case-study-image-prev]');
        const nextButton = dialog.querySelector('[data-case-study-image-next]');
        let currentIndex = 0;
        let lastTrigger = null;

        function renderImage(nextIndex) {
            currentIndex = (nextIndex + triggers.length) % triggers.length;
            const trigger = triggers[currentIndex];
            const imageTitle = trigger.dataset.imageTitle || 'Xem ảnh minh chứng';
            const imageCaption = trigger.dataset.imageCaption || '';
            const hasMultipleImages = triggers.length > 1;

            image.src = trigger.dataset.imageSrc || trigger.href;
            image.alt = trigger.dataset.imageAlt || imageTitle;
            title.textContent = imageTitle;
            caption.textContent = imageCaption;
            caption.hidden = imageCaption === '';
            count.textContent = hasMultipleImages ? `Ảnh ${currentIndex + 1} trên ${triggers.length}` : '';
            previousButton.hidden = !hasMultipleImages;
            nextButton.hidden = !hasMultipleImages;
        }

        function openDialog(trigger) {
            currentIndex = triggers.indexOf(trigger);
            lastTrigger = trigger;
            renderImage(currentIndex);
            dialog.showModal();
        }

        triggers.forEach(function (trigger) {
            trigger.addEventListener('click', function (event) {
                if (event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
                event.preventDefault();
                openDialog(trigger);
            });
        });

        previousButton.addEventListener('click', function () {
            renderImage(currentIndex - 1);
        });

        nextButton.addEventListener('click', function () {
            renderImage(currentIndex + 1);
        });

        dialog.addEventListener('click', function (event) {
            if (event.target === dialog) dialog.close();
        });

        dialog.addEventListener('keydown', function (event) {
            if (triggers.length < 2) return;
            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                renderImage(currentIndex - 1);
            }
            if (event.key === 'ArrowRight') {
                event.preventDefault();
                renderImage(currentIndex + 1);
            }
        });

        dialog.addEventListener('close', function () {
            if (lastTrigger) lastTrigger.focus({ preventScroll: true });
        });
    })();


  
  })(jQuery); // End jQuery
