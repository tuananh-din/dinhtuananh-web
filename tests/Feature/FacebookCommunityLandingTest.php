<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Database\Seeders\FacebookCommunityCourseSeeder;
use Database\Seeders\PublishFacebookCommunityCourseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacebookCommunityLandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_course_uses_the_community_landing_page(): void
    {
        $this->seed(PublishFacebookCommunityCourseSeeder::class);

        $this->get(route('course.detail', 'facebook-community-growth-system'))
            ->assertOk()
            ->assertViewIs('courses.facebook-community')
            ->assertSee('Xây cộng đồng.')
            ->assertSee('/site/assets/img/courses/facebook-community-hero.webp', false);
    }

    public function test_draft_course_is_available_to_admin_preview(): void
    {
        $this->seed(FacebookCommunityCourseSeeder::class);
        $course = Course::where('slug', 'facebook-community-growth-system')->firstOrFail();

        $this->get(route('course.detail', $course->slug))->assertNotFound();
        $this->actingAs(User::factory()->create())
            ->get(route('course.preview', $course->id))
            ->assertOk()
            ->assertViewIs('courses.facebook-community')
            ->assertSee('Bản xem trước — khóa học chưa mở');
    }
}
