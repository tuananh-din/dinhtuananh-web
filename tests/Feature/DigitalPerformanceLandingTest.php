<?php

namespace Tests\Feature;

use App\Models\About;
use App\Models\Course;
use App\Models\User;
use Database\Seeders\DigitalPerformanceCourseSeeder;
use Database\Seeders\PublishDigitalPerformanceCourseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DigitalPerformanceLandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_publication_adds_course_image_price_and_link_to_listing(): void
    {
        $this->seed(PublishDigitalPerformanceCourseSeeder::class);
        $this->get(route('courses'))->assertOk()
            ->assertSee('Digital Performance Management')->assertSee('3.000.000')
            ->assertSee('/site/assets/img/courses/digital-performance-workspace.png', false)
            ->assertSee(route('course.detail', 'digital-performance-management'), false);
        $this->get(route('course.detail', 'digital-performance-management'))->assertOk()
            ->assertViewIs('courses.digital-performance')->assertDontSee('Bản xem trước');
    }

    public function test_publication_preserves_existing_edits_and_other_courses(): void
    {
        $this->seed(DigitalPerformanceCourseSeeder::class);
        $course = Course::where('slug', 'digital-performance-management')->firstOrFail();
        $course->update(['price' => 3200000, 'thumbnail' => '/storage/custom.png', 'content' => 'Đã chỉnh']);
        $other = Course::create(['title' => 'Other', 'slug' => 'other', 'is_active' => false]);
        $this->seed(PublishDigitalPerformanceCourseSeeder::class);
        $this->seed(PublishDigitalPerformanceCourseSeeder::class);
        $this->assertDatabaseCount('courses', 2);
        $this->assertDatabaseHas('courses', ['id' => $course->id, 'price' => 3200000,
            'thumbnail' => '/storage/custom.png', 'content' => 'Đã chỉnh', 'is_active' => true]);
        $this->assertFalse((bool) $other->fresh()->is_active);
    }

    public function test_draft_is_private_but_admin_can_preview_the_landing(): void
    {
        $this->seed(DigitalPerformanceCourseSeeder::class);
        $course = Course::where('slug', 'digital-performance-management')->firstOrFail();
        $this->get(route('course.detail', $course->slug))->assertNotFound();
        $this->get(route('course.preview', $course->id))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())
            ->get(route('course.preview', $course->id))->assertOk()
            ->assertViewIs('courses.digital-performance')
            ->assertSee('Bản xem trước — khóa học chưa mở')->assertSee('3.000.000');
    }

    public function test_published_landing_reads_profile_and_current_price_and_links_lead_to_course(): void
    {
        Mail::fake();
        About::create(['name' => 'Đinh Tuấn Anh', 'description' => 'Hồ sơ thật từ database.', 'about_me' => '<p>Kinh nghiệm đã cập nhật.</p>', 'avatar' => 'storage/images/instructor.jpg']);
        $this->seed(DigitalPerformanceCourseSeeder::class);
        $course = Course::where('slug', 'digital-performance-management')->firstOrFail();
        $course->update(['is_active' => true, 'price' => 3500000]);
        $this->get(route('course.detail', $course->slug))->assertOk()
            ->assertViewIs('courses.digital-performance')->assertSee('Hồ sơ thật từ database.')
            ->assertSee('Kinh nghiệm đã cập nhật.')->assertSee('3.500.000')
            ->assertDontSee('3.000.000')->assertSee(url('storage/images/instructor.jpg'))
            ->assertSee('name="course_id" value="'.$course->id.'"', false);
        $this->post(route('lead.store'), [
            'course_id' => $course->id, 'name' => 'Học viên thử nghiệm', 'phone' => '0901234567',
            'source_page' => 'digital_performance_landing',
        ])->assertRedirect(route('thank.you'));
        $this->assertDatabaseHas('leads', ['course_id' => $course->id, 'source_page' => 'digital_performance_landing']);
    }

    public function test_reseeding_preserves_admin_edits(): void
    {
        $this->seed(DigitalPerformanceCourseSeeder::class);
        $course = Course::where('slug', 'digital-performance-management')->firstOrFail();
        $course->update(['price' => 3200000, 'is_active' => true, 'content' => 'Nội dung đã sửa']);
        $this->seed(DigitalPerformanceCourseSeeder::class);
        $this->assertDatabaseCount('courses', 1);
        $this->assertDatabaseHas('courses', ['id' => $course->id, 'price' => 3200000, 'is_active' => true, 'content' => 'Nội dung đã sửa']);
    }
}
