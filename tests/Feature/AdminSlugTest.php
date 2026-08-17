<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_accepts_a_custom_slug_when_created_and_edited(): void
    {
        $user = User::factory()->create();
        $blog = Blog::create([
            'title' => 'Bài viết gốc',
            'slug' => 'slug-ban-dau',
            'content' => 'Nội dung gốc',
        ]);

        $this->actingAs($user)->post(route('blog.store'), [
            'title' => 'Bài viết có đường dẫn riêng',
            'slug' => 'Đường Dẫn Tự Nhập!',
            'content' => 'Nội dung mới',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('blogs', [
            'slug' => 'duong-dan-tu-nhap',
        ]);

        $this->actingAs($user)->post(route('blog.store'), [
            'id' => $blog->id,
            'title' => 'Tiêu đề hoàn toàn mới',
            'slug' => 'Đường Dẫn Đã Sửa',
            'content' => 'Nội dung đã sửa',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('blogs', [
            'id' => $blog->id,
            'slug' => 'duong-dan-da-sua',
        ]);

        Blog::create([
            'title' => 'Đường dẫn trùng',
            'slug' => 'duong-dan-trung',
            'content' => 'Nội dung có sẵn',
        ]);

        $this->actingAs($user)->post(route('blog.store'), [
            'title' => 'Tiêu đề fallback',
            'slug' => 'Đường Dẫn Trùng',
            'content' => 'Nội dung trùng',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('blogs', ['slug' => 'duong-dan-trung-1']);

        $this->actingAs($user)->post(route('blog.store'), [
            'title' => 'Tiêu đề fallback',
            'content' => 'Nội dung tự sinh đường dẫn',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('blogs', ['slug' => 'tieu-de-fallback']);
    }

    public function test_course_normalizes_a_custom_slug_and_falls_back_to_title(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('course.store'), [
            'title' => 'Khóa học Facebook Ads',
            'slug' => 'Khóa Học Tự Nhập!',
            'short_description' => 'Mô tả ngắn',
        ])->assertSessionHasNoErrors();

        $this->actingAs($user)->post(route('course.store'), [
            'title' => 'Khóa học Facebook Ads',
            'short_description' => 'Mô tả ngắn khác',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('courses', ['slug' => 'khoa-hoc-tu-nhap']);
        $this->assertDatabaseHas('courses', ['slug' => 'khoa-hoc-facebook-ads']);
    }

    public function test_blog_adds_a_suffix_when_a_custom_slug_is_already_in_use(): void
    {
        $user = User::factory()->create();
        Blog::create([
            'title' => 'Bài viết có sẵn',
            'slug' => 'duong-dan-da-dung',
            'content' => 'Nội dung có sẵn',
        ]);

        $this->actingAs($user)->post(route('blog.store'), [
            'title' => 'Bài viết mới',
            'slug' => 'Đường Dẫn Đã Dùng',
            'content' => 'Nội dung mới',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('blogs', ['slug' => 'duong-dan-da-dung-1']);
    }

    public function test_course_can_update_its_custom_slug(): void
    {
        $user = User::factory()->create();
        $course = Course::create([
            'title' => 'Khóa học hiện có',
            'slug' => 'khoa-hoc-cu',
            'short_description' => 'Mô tả ngắn',
        ]);

        $this->actingAs($user)->post(route('course.store'), [
            'id' => $course->id,
            'title' => 'Khóa học hiện có',
            'slug' => 'Khóa Học Đường Dẫn Mới',
            'short_description' => 'Mô tả ngắn đã sửa',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'slug' => 'khoa-hoc-duong-dan-moi',
        ]);
    }
}
