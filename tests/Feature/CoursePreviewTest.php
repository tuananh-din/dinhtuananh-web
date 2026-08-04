<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoursePreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_preview_inactive_course(): void
    {
        $course = Course::create(['title' => 'Khóa học nháp', 'slug' => 'khoa-hoc-nhap', 'short_description' => 'Mô tả', 'is_active' => false]);

        $this->actingAs(User::factory()->create())->get(route('course.preview', $course->id))
            ->assertOk()->assertSee('Bản xem trước — khóa học chưa mở');
    }

    public function test_guest_cannot_preview_and_public_inactive_course_is_404(): void
    {
        $course = Course::create(['title' => 'Khóa học riêng', 'slug' => 'khoa-hoc-rieng', 'short_description' => 'Mô tả', 'is_active' => false]);

        $this->get(route('course.preview', $course->id))->assertRedirect(route('login'));
        $this->get(route('course.detail', $course->slug))->assertNotFound();
    }

    public function test_admin_course_index_filters_by_title_and_status(): void
    {
        Course::create(['title' => 'Khóa học đang mở', 'slug' => 'khoa-mo', 'short_description' => 'Mô tả', 'is_active' => true]);
        Course::create(['title' => 'Khóa học tắt', 'slug' => 'khoa-tat', 'short_description' => 'Mô tả', 'is_active' => false]);

        $this->actingAs(User::factory()->create())->get(route('admin.course', ['search' => 'đang mở', 'status' => 'active']))
            ->assertOk()->assertSee('Khóa học đang mở')->assertDontSee('Khóa học tắt');
    }
}
