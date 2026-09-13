<?php

namespace Tests\Feature;

use App\Models\About;
use App\Models\Blog;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSeoJsonLdTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_detail_renders_title_canonical_og_and_article_json_ld(): void
    {
        $this->createSiteIdentity();
        $blog = Blog::create([
            'title' => 'Bài viết SEO',
            'slug' => 'bai-viet-seo',
            'description' => 'Mô tả bài viết cho mạng xã hội.',
            'content' => '<p>Nội dung bài viết</p>',
            'image' => '/storage/images/blog.jpg',
        ]);

        $response = $this->get(route('blog', $blog->slug));

        $response->assertOk();
        $response->assertSee('<title>Bài viết SEO | Thương hiệu thử nghiệm</title>', false);
        $response->assertSee('<link rel="canonical" href="http://localhost/bai-viet-seo.html">', false);
        $response->assertSee('<meta property="og:type" content="article">', false);
        $response->assertSee('<meta property="og:title" content="Bài viết SEO">', false);
        $response->assertSee('<meta property="og:image" content="http://localhost/storage/images/blog.jpg">', false);
        $response->assertSee('"@type":"Article"', false);
        $response->assertSee('"headline":"Bài viết SEO"', false);
        $response->assertSee('"@id":"http://localhost/bai-viet-seo.html"', false);
    }

    public function test_home_renders_site_seo_metadata_and_person_json_ld(): void
    {
        $this->createSiteIdentity();

        $response = $this->get(route('index'));

        $response->assertOk();
        $response->assertSee('<title>Thương hiệu thử nghiệm</title>', false);
        $response->assertSee('<meta name="description" content="Mô tả SEO của website.">', false);
        $response->assertSee('<link rel="canonical" href="http://localhost">', false);
        $response->assertSee('<meta property="og:type" content="website">', false);
        $response->assertSee('<meta property="og:title" content="Thương hiệu thử nghiệm">', false);
        $response->assertSee('"@type":"Person"', false);
        $response->assertSee('<meta property="og:image" content="http://localhost/storage/images/og.jpg">', false);
        $response->assertSee('<meta name="twitter:card" content="summary_large_image">', false);
        $response->assertSee('"name":"Nguyễn Thử Nghiệm"', false);
        $response->assertSee('"sameAs":["https://example.test/profile"]', false);
    }

    public function test_about_renders_its_own_seo_metadata_and_person_json_ld(): void
    {
        $this->createSiteIdentity();
        About::firstOrFail()->update([
            'title_seo' => 'Học Digital Marketing cùng Nguyễn Thử Nghiệm',
            'desc_seo' => 'Thông tin giảng dạy và nội dung Digital Marketing thực hành.',
            'avatar' => '/storage/images/about-profile.jpg',
        ]);

        $response = $this->get(route('about'));

        $response->assertOk();
        $response->assertSee('<title>Học Digital Marketing cùng Nguyễn Thử Nghiệm</title>', false);
        $response->assertSee('<meta name="description" content="Thông tin giảng dạy và nội dung Digital Marketing thực hành.">', false);
        $response->assertSee('<meta property="og:title" content="Học Digital Marketing cùng Nguyễn Thử Nghiệm">', false);
        $response->assertSee('<meta property="og:description" content="Thông tin giảng dạy và nội dung Digital Marketing thực hành.">', false);
        $response->assertSee('<link rel="canonical" href="http://localhost/about">', false);
        $response->assertSee('<meta property="og:image" content="http://localhost/storage/images/about-profile.jpg">', false);
        $response->assertSee('"@type":"Person"', false);
    }

    public function test_about_escapes_dynamic_seo_metadata(): void
    {
        $this->createSiteIdentity();
        About::firstOrFail()->update([
            'title_seo' => '<script>alert("title")</script>',
            'desc_seo' => 'Mô tả "không an toàn" <b>cần được thoát</b>.',
        ]);

        $response = $this->get(route('about'));

        $response->assertOk()
            ->assertDontSee('<script>alert("title")</script>', false)
            ->assertSee('&lt;script&gt;alert(&quot;title&quot;)&lt;/script&gt;', false)
            ->assertSee('Mô tả &quot;không an toàn&quot; cần được thoát.', false);
    }

    public function test_about_uses_non_empty_seo_fallbacks(): void
    {
        $this->createSiteIdentity();
        About::firstOrFail()->update([
            'title_seo' => '   ',
            'desc_seo' => '   ',
            'description' => 'Mô tả giới thiệu dự phòng.',
        ]);

        $response = $this->get(route('about'));

        $response->assertOk()
            ->assertSee('<title>Giới thiệu | Nguyễn Thử Nghiệm</title>', false)
            ->assertSee('<meta name="description" content="Mô tả giới thiệu dự phòng.">', false);
    }

    public function test_about_uses_default_metadata_when_cms_content_has_no_text(): void
    {
        $this->createSiteIdentity();
        About::firstOrFail()->update([
            'desc_seo' => '<p><br></p>',
            'description' => '<div>   </div>',
        ]);

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('<meta name="description" content="Thông tin giảng dạy và nội dung Digital Marketing của Nguyễn Thử Nghiệm.">', false);
    }

    public function test_about_uses_a_name_fallback_when_profile_and_site_name_are_blank(): void
    {
        $this->createSiteIdentity();
        Setting::firstOrFail()->update(['name' => '   ']);
        About::firstOrFail()->update(['name' => '   ']);

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('<title>Giới thiệu | Tuấn Anh</title>', false);
    }

    public function test_draft_blog_is_hidden_from_public_detail_and_listing(): void
    {
        $this->createSiteIdentity();
        $draft = Blog::create([
            'title' => 'Bài nháp', 'slug' => 'bai-nhap', 'content' => 'Nội dung nháp', 'is_published' => false,
        ]);

        $this->get(route('blog', $draft->slug))->assertNotFound();
        $this->get(route('blogs'))->assertDontSee('Bài nháp');
    }

    public function test_rss_feed_contains_published_blogs_only(): void
    {
        $this->createSiteIdentity();
        Blog::create(['title' => 'Bài RSS', 'slug' => 'bai-rss', 'description' => 'Mô tả RSS', 'content' => 'Nội dung', 'is_published' => true]);
        Blog::create(['title' => 'Bài nháp RSS', 'slug' => 'bai-nhap-rss', 'description' => 'Nháp', 'content' => 'Nội dung', 'is_published' => false]);

        $this->get(route('feed'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/rss+xml; charset=UTF-8')
            ->assertSee('Bài RSS')
            ->assertDontSee('Bài nháp RSS');
    }

    public function test_blog_index_shows_empty_state_when_no_published_posts_match(): void
    {
        $this->createSiteIdentity();
        $this->get(route('blogs', ['search' => 'khong-co']))
            ->assertOk()->assertSee('Chưa có bài viết trong mục này.')->assertSee('Xem tất cả bài viết');
    }

    private function createSiteIdentity(): void
    {
        Setting::create([
            'name' => 'Thương hiệu thử nghiệm',
            'url' => 'https://example.test',
            'desc_seo' => 'Mô tả SEO của website.',
            'logo' => '/storage/images/logo.jpg',
            'og_image' => '/storage/images/og.jpg',
        ]);

        About::create([
            'name' => 'Nguyễn Thử Nghiệm',
            'description' => 'Chuyên gia marketing',
            'about_me' => 'Giới thiệu ngắn',
            'facebook' => 'https://example.test/profile',
        ]);
    }
}
