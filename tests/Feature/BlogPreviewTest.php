<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogPreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_preview_draft_blog(): void
    {
        $blog = Blog::create(['title' => 'Bài nháp preview', 'slug' => 'bai-nhap-preview', 'content' => 'Nội dung', 'is_published' => false]);

        $this->actingAs(User::factory()->create())
            ->get(route('blog.preview', $blog->id))
            ->assertOk()
            ->assertSee('Bản xem trước — bài chưa đăng');
    }

    public function test_guest_cannot_preview_draft_blog_and_public_url_is_404(): void
    {
        $blog = Blog::create(['title' => 'Bài nháp private', 'slug' => 'bai-nhap-private', 'content' => 'Nội dung', 'is_published' => false]);

        $this->get(route('blog.preview', $blog->id))->assertRedirect(route('login'));
        $this->get(route('blog', $blog->slug))->assertNotFound();
    }

    public function test_admin_blog_index_filters_by_search_status_and_category(): void
    {
        $category = Category::create(['name' => 'SEO', 'slug' => 'seo']);
        $match = Blog::create(['title' => 'Bài SEO nháp', 'slug' => 'bai-seo-nhap', 'content' => 'Nội dung', 'is_published' => false]);
        $match->categories()->attach($category);
        Blog::create(['title' => 'Bài khác', 'slug' => 'bai-khac', 'content' => 'Nội dung', 'is_published' => true]);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.blog', ['search' => 'SEO', 'status' => 'draft', 'category' => 'seo']))
            ->assertOk()->assertSee('Bài SEO nháp')->assertDontSee('Bài khác');
    }
}
