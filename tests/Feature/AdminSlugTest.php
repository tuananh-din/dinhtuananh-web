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

    public function test_blog_keeps_its_slug_when_edited_and_adds_suffix_for_duplicate_title(): void
    {
        $user = User::factory()->create();
        $blog = Blog::create([
            'title' => 'Bài viết gốc',
            'slug' => 'slug-ban-dau',
            'content' => 'Nội dung gốc',
        ]);

        $this->actingAs($user)->post(route('blog.store'), [
            'id' => $blog->id,
            'title' => 'Tiêu đề hoàn toàn mới',
            'content' => 'Nội dung đã sửa',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('blogs', [
            'id' => $blog->id,
            'slug' => 'slug-ban-dau',
        ]);

        $this->actingAs($user)->post(route('blog.store'), [
            'title' => 'Tiêu đề hoàn toàn mới',
            'content' => 'Nội dung trùng thứ nhất',
        ])->assertSessionHasNoErrors();

        $this->actingAs($user)->post(route('blog.store'), [
            'title' => 'Tiêu đề hoàn toàn mới',
            'content' => 'Nội dung trùng thứ hai',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('blogs', ['slug' => 'tieu-de-hoan-toan-moi']);
        $this->assertDatabaseHas('blogs', ['slug' => 'tieu-de-hoan-toan-moi-1']);
    }

    public function test_course_generates_slug_from_title_and_adds_suffix_for_duplicate_slug(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('course.store'), [
            'title' => 'Khóa học Facebook Ads',
            'short_description' => 'Mô tả ngắn',
        ])->assertSessionHasNoErrors();

        $this->actingAs($user)->post(route('course.store'), [
            'title' => 'Khóa học Facebook Ads',
            'short_description' => 'Mô tả ngắn khác',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('courses', ['slug' => 'khoa-hoc-facebook-ads']);
        $this->assertDatabaseHas('courses', ['slug' => 'khoa-hoc-facebook-ads-1']);
    }
}
