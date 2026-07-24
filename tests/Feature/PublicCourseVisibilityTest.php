<?php

namespace Tests\Feature;

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCourseVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_list_only_shows_active_courses(): void
    {
        $activeCourse = $this->createCourse('khoa-hoc-active', true);
        $inactiveCourse = $this->createCourse('khoa-hoc-inactive', false);

        $response = $this->get(route('courses'));

        $response->assertOk();
        $response->assertSee($activeCourse->title);
        $response->assertDontSee($inactiveCourse->title);
    }

    public function test_only_active_courses_have_a_public_detail_page(): void
    {
        $activeCourse = $this->createCourse('khoa-hoc-active', true);
        $inactiveCourse = $this->createCourse('khoa-hoc-inactive', false);

        $this->get(route('course.detail', $activeCourse->slug))->assertOk();
        $this->get(route('course.detail', $inactiveCourse->slug))->assertNotFound();
    }

    private function createCourse(string $slug, bool $isActive): Course
    {
        return Course::create([
            'title' => 'Khóa học '.$slug,
            'slug' => $slug,
            'short_description' => 'Mô tả ngắn.',
            'is_active' => $isActive,
        ]);
    }
}
