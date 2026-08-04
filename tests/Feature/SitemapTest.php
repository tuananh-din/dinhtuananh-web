<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_includes_blogs_and_active_courses_only(): void
    {
        $blog = Blog::create([
            'title' => 'Bài viết sitemap',
            'slug' => 'bai-viet-sitemap',
        ]);
        $category = Category::create(['name' => 'Marketing', 'slug' => 'marketing']);
        $blog->categories()->attach($category);
        $activeCourse = Course::create([
            'title' => 'Khóa học active',
            'slug' => 'khoa-hoc-active',
            'is_active' => true,
        ]);
        $inactiveCourse = Course::create([
            'title' => 'Khóa học inactive',
            'slug' => 'khoa-hoc-inactive',
            'is_active' => false,
        ]);

        $response = $this->get(route('sitemap'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $response->assertSee(route('blog', $blog->slug), false);
        $response->assertSee(route('blogs', ['category' => $category->slug]), false);
        $response->assertSee(route('course.detail', $activeCourse->slug), false);
        $response->assertDontSee(route('course.detail', $inactiveCourse->slug), false);
    }
}
