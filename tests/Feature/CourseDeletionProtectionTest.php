<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseDeletionProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_hides_a_course_that_has_leads_instead_of_deleting_it(): void
    {
        $user = User::factory()->create();
        $course = $this->createCourse('facebook-ads');
        $lead = Lead::create([
            'course_id' => $course->id,
            'name' => 'Nguyen Van A',
            'phone' => '0900000000',
            'status' => 'new',
        ]);

        $response = $this->actingAs($user)->post(route('course.delete', $course));

        $response->assertSessionHas('success', 'Khóa học đã có lead nên được ẩn thay vì xóa.');
        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'is_active' => 0,
        ]);
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'course_id' => $course->id,
        ]);
    }

    public function test_admin_can_delete_a_course_that_has_no_leads(): void
    {
        $user = User::factory()->create();
        $course = $this->createCourse('google-ads');

        $response = $this->actingAs($user)->post(route('course.delete', $course));

        $response->assertSessionHas('success', 'Xóa khóa học thành công.');
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    private function createCourse(string $slug): Course
    {
        return Course::create([
            'title' => 'Khóa học '.$slug,
            'slug' => $slug,
            'short_description' => 'Mô tả ngắn.',
        ]);
    }
}
