<?php

namespace Tests\Feature;

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessibilityBaselineTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_layout_exposes_skip_link_and_accessible_mobile_menu(): void
    {
        $this->get(route('index'))
            ->assertOk()
            ->assertSee('href="#main-content"', false)
            ->assertSee('role="dialog"', false)
            ->assertSee('aria-label="Menu điều hướng"', false)
            ->assertSee('aria-controls="site-mobile-menu"', false);
    }

    public function test_course_pagination_marks_current_and_disabled_items_without_empty_links(): void
    {
        foreach (range(1, 13) as $number) {
            Course::create([
                'title' => "Khóa học {$number}",
                'slug' => "khoa-hoc-{$number}",
                'short_description' => 'Mô tả khóa học',
                'is_active' => true,
            ]);
        }

        $this->get(route('courses'))
            ->assertOk()
            ->assertSee('aria-label="Phân trang khóa học"', false)
            ->assertSee('aria-current="page"', false)
            ->assertSee('aria-label="Trang sau"', false)
            ->assertDontSee('<a class="page-numbers"><i class="fa-solid fa-chevron-left"></i></a>', false);
    }
}
