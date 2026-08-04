<?php

namespace Tests\Feature;

use App\Models\Blog;
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
}
