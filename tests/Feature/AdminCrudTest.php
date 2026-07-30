<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Course;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_crud_routes(): void
    {
        foreach ([
            'blog.create',
            'course.create',
            'testimonial.create',
            'service.create',
            'skill.create',
        ] as $route) {
            $this->get(route($route))->assertRedirect(route('login'));
        }
    }

    public function test_blog_requires_required_fields_and_stores_then_updates_data(): void
    {
        $user = User::factory()->create();

        $this->from(route('blog.create'))
            ->actingAs($user)
            ->post(route('blog.store'), ['title' => ''])
            ->assertRedirect(route('blog.create'))
            ->assertSessionHasErrors(['title', 'content']);

        $this->actingAs($user)->post(route('blog.store'), [
            'title' => 'Bài viết thử nghiệm',
            'description' => 'Mô tả ban đầu',
            'content' => '<p>Nội dung ban đầu</p>',
            'title_seo' => 'SEO ban đầu',
        ])->assertSessionHasNoErrors();

        $blog = Blog::firstOrFail();
        $this->assertDatabaseHas('blogs', [
            'id' => $blog->id,
            'title' => 'Bài viết thử nghiệm',
            'description' => 'Mô tả ban đầu',
            'content' => '<p>Nội dung ban đầu</p>',
            'title_seo' => 'SEO ban đầu',
        ]);

        $this->actingAs($user)->post(route('blog.store'), [
            'id' => $blog->id,
            'title' => 'Bài viết đã sửa',
            'description' => 'Mô tả đã sửa',
            'content' => '<p>Nội dung đã sửa</p>',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('blogs', [
            'id' => $blog->id,
            'title' => 'Bài viết đã sửa',
            'description' => 'Mô tả đã sửa',
            'content' => '<p>Nội dung đã sửa</p>',
        ]);
    }

    public function test_course_rejects_invalid_data_and_stores_then_updates_data(): void
    {
        $user = User::factory()->create();

        $this->from(route('course.create'))
            ->actingAs($user)
            ->post(route('course.store'), ['title' => '', 'short_description' => ''])
            ->assertRedirect(route('course.create'))
            ->assertSessionHasErrors(['title', 'short_description']);

        $this->actingAs($user)->post(route('course.store'), $this->coursePayload([
            'title' => 'Khóa học thử nghiệm',
            'price' => 200000,
            'sale_price' => 150000,
            'sort_order' => 3,
        ]))->assertSessionHasNoErrors();

        $course = Course::firstOrFail();
        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Khóa học thử nghiệm',
            'price' => 200000,
            'sale_price' => 150000,
            'sort_order' => 3,
            'is_featured' => 1,
            'is_active' => 1,
        ]);

        $updatePayload = $this->coursePayload([
            'id' => $course->id,
            'title' => 'Khóa học đã sửa',
            'sort_order' => 1,
        ]);
        unset($updatePayload['is_featured'], $updatePayload['is_active']);

        $this->actingAs($user)->post(route('course.store'), $updatePayload)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Khóa học đã sửa',
            'sort_order' => 1,
            'is_featured' => 0,
            'is_active' => 0,
        ]);
    }

    public function test_testimonial_rejects_invalid_data_and_stores_then_updates_data(): void
    {
        $user = User::factory()->create();

        $this->from(route('testimonial.create'))
            ->actingAs($user)
            ->post(route('testimonial.store'), ['name' => '', 'content' => '', 'rating' => 6])
            ->assertRedirect(route('testimonial.create'))
            ->assertSessionHasErrors(['name', 'content', 'rating']);

        $this->actingAs($user)->post(route('testimonial.store'), [
            'name' => 'Nguyễn An',
            'job_title' => 'Founder',
            'company' => 'Công ty A',
            'content' => 'Nội dung nhận xét',
            'rating' => 5,
            'sort_order' => 2,
            'is_featured' => '1',
            'is_active' => '1',
        ])->assertSessionHasNoErrors();

        $testimonial = Testimonial::firstOrFail();
        $this->assertDatabaseHas('testimonials', [
            'id' => $testimonial->id,
            'name' => 'Nguyễn An',
            'rating' => 5,
            'is_featured' => 1,
            'is_active' => 1,
        ]);

        $this->actingAs($user)->post(route('testimonial.store'), [
            'id' => $testimonial->id,
            'name' => 'Nguyễn Bình',
            'content' => 'Nội dung đã sửa',
            'rating' => 4,
            'sort_order' => 1,
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('testimonials', [
            'id' => $testimonial->id,
            'name' => 'Nguyễn Bình',
            'content' => 'Nội dung đã sửa',
            'rating' => 4,
            'is_featured' => 0,
            'is_active' => 0,
        ]);
    }

    public function test_service_requires_title_and_stores_then_updates_data(): void
    {
        $user = User::factory()->create();

        $this->from(route('service.create'))
            ->actingAs($user)
            ->post(route('service.store'), ['title' => ''])
            ->assertRedirect(route('service.create'))
            ->assertSessionHasErrors('title');

        $this->actingAs($user)->post(route('service.store'), [
            'title' => 'Tư vấn quảng cáo',
            'description' => 'Mô tả dịch vụ',
        ])->assertSessionHasNoErrors();

        $service = Service::firstOrFail();
        $this->actingAs($user)->post(route('service.store'), [
            'id' => $service->id,
            'title' => 'Đào tạo quảng cáo',
            'description' => 'Mô tả đã sửa',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'title' => 'Đào tạo quảng cáo',
            'description' => 'Mô tả đã sửa',
        ]);
    }

    public function test_skill_rejects_invalid_number_and_stores_then_updates_data(): void
    {
        $user = User::factory()->create();

        $this->from(route('skill.create'))
            ->actingAs($user)
            ->post(route('skill.store'), ['name' => '', 'number' => 'not-a-number'])
            ->assertRedirect(route('skill.create'))
            ->assertSessionHasErrors(['name', 'number']);

        $this->actingAs($user)->post(route('skill.store'), [
            'name' => 'Facebook Ads',
            'description' => 'Kỹ năng ban đầu',
            'number' => 80,
        ])->assertSessionHasNoErrors();

        $skill = Skill::firstOrFail();
        $this->actingAs($user)->post(route('skill.store'), [
            'id' => $skill->id,
            'name' => 'Google Ads',
            'description' => 'Kỹ năng đã sửa',
            'number' => 90,
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('skills', [
            'id' => $skill->id,
            'name' => 'Google Ads',
            'description' => 'Kỹ năng đã sửa',
            'number' => 90,
        ]);
    }

    private function coursePayload(array $attributes = []): array
    {
        return array_merge([
            'title' => 'Khóa học mặc định',
            'short_description' => 'Mô tả ngắn cho khóa học.',
            'is_featured' => '1',
            'is_active' => '1',
        ], $attributes);
    }
}
