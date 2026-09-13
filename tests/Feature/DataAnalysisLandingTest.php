<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Database\Seeders\DataAnalysisCourseSeeder;
use Database\Seeders\PublishDataAnalysisCourseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataAnalysisLandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_course_uses_the_data_analysis_landing_page(): void
    {
        $this->seed(PublishDataAnalysisCourseSeeder::class);

        $this->get(route('course.detail', 'data-analysis-visualization'))
            ->assertOk()
            ->assertViewIs('courses.data-analysis')
            ->assertSee('Hiểu dữ liệu.')
            ->assertSee('/site/assets/img/courses/digital-performance-workspace.png', false);
    }

    public function test_draft_course_is_available_to_admin_preview(): void
    {
        $this->seed(DataAnalysisCourseSeeder::class);
        $course = Course::where('slug', 'data-analysis-visualization')->firstOrFail();

        $this->get(route('course.detail', $course->slug))->assertNotFound();
        $this->actingAs(User::factory()->create())
            ->get(route('course.preview', $course->id))
            ->assertOk()
            ->assertViewIs('courses.data-analysis')
            ->assertSee('Bản xem trước — khóa học chưa mở');
    }

    public function test_landing_keeps_native_curriculum_disclosure_semantics_and_initial_state(): void
    {
        $this->seed(PublishDataAnalysisCourseSeeder::class);

        $content = $this->get(route('course.detail', 'data-analysis-visualization'))
            ->assertOk()
            ->getContent();

        $this->assertMatchesRegularExpression('/<div class="dpm-curriculum">\s*<details\s+open\s*>\s*<summary><span class="dpm-module-number">Buổi 01<\/span><span class="dpm-module-title">/', $content);
        $this->assertSame(0, preg_match('/<summary>(?:(?!<\/summary>).)*<h[1-6]\b/s', $content));
        $this->assertStringContainsString('<details><summary>Tôi không học Data chuyên sâu có học được không?</summary>', $content);
        $this->assertSame(1, preg_match_all('/<details\b[^>]*\bopen\b[^>]*>/', $content));
    }
}
