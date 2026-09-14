<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPerformanceMarkupTest extends TestCase
{
    public function test_public_motion_paths_default_to_native_on_touch_and_reduced_motion(): void
    {
        $mainScript = file_get_contents(public_path('site/assets/js/main.js'));
        $layout = file_get_contents(resource_path('views/layouts/master.blade.php'));

        $this->assertNotFalse($mainScript);
        $this->assertNotFalse($layout);
        $this->assertStringContainsString('const canUseEnhancedMotion', $mainScript);
        $this->assertStringContainsString('const canUseGsapMotion', $mainScript);
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*canUseGsapMotion\s*\)\s*\{\s*const hasSmoothScrollShell/',
            $mainScript
        );
        $this->assertStringContainsString("if (canUseGsapMotion && $('.gt-project-area').length > 0)", $mainScript);
        $this->assertStringContainsString("typeof window.Swiper === 'function'", $mainScript);
        $this->assertStringContainsString("window.addEventListener('scroll', requestScrollStateUpdate, { passive: true })", $mainScript);
        preg_match_all('/(?:\\.on|addEventListener)\s*\(\s*[\'\"]scroll[\'\"]/', $mainScript, $scrollListeners);
        $this->assertCount(1, $scrollListeners[0]);
        $this->assertStringContainsString("behavior: canUseEnhancedMotion ? 'smooth' : 'auto'", $mainScript);
        $this->assertStringContainsString("behavior: canUseEnhancedMotion ? 'smooth' : 'auto'", $layout);
    }

    public function test_home_typing_stops_when_its_section_or_tab_is_not_visible(): void
    {
        $homeView = file_get_contents(resource_path('views/home.blade.php'));

        $this->assertNotFalse($homeView);
        $this->assertStringContainsString('IntersectionObserver', $homeView);
        $this->assertStringContainsString("document.addEventListener('visibilitychange'", $homeView);
        $this->assertStringContainsString("window.addEventListener('pagehide'", $homeView);
        $this->assertStringContainsString('function stopTyping()', $homeView);
        $this->assertStringContainsString('observer.disconnect()', $homeView);
    }

    public function test_public_forms_have_delayed_accessible_loading_feedback(): void
    {
        $layout = file_get_contents(resource_path('views/layouts/master.blade.php'));
        $header = file_get_contents(resource_path('views/layouts/header.blade.php'));
        $blogView = file_get_contents(resource_path('views/blogs.blade.php'));
        $styles = file_get_contents(public_path('site/assets/css/custom.css'));

        $this->assertStringContainsString("form.setAttribute('aria-busy', 'true')", $layout);
        $this->assertStringContainsString("window.setTimeout(function ()", $layout);
        $this->assertStringContainsString("}, 250)", $layout);
        $this->assertStringContainsString("status.setAttribute('role', 'status')", $layout);
        $this->assertStringContainsString('role="status" aria-live="polite" aria-label="Đang tải trang"', $header);
        $this->assertStringContainsString('data-submit-label="Đang tìm bài viết…"', $blogView);
        $this->assertStringContainsString('.form-loading-spinner', $styles);
        $this->assertStringContainsString('@keyframes form-loading-spin', $styles);
    }
}
