<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogKnowledgeHubTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_listing_renders_knowledge_hub_metadata_and_public_categories(): void
    {
        $this->createSiteIdentity();
        $publicCategory = Category::create(['name' => 'Performance', 'slug' => 'performance']);
        $draftCategory = Category::create(['name' => 'Chủ đề nháp', 'slug' => 'chu-de-nhap']);
        $published = $this->createBlog('Bài viết công khai', 'bai-viet-cong-khai', true, null, '<p>Nội dung fallback từ bài viết.</p>');
        $published->categories()->attach($publicCategory);
        $draft = $this->createBlog('Bài viết nháp', 'bai-viet-nhap', false);
        $draft->categories()->attach($draftCategory);

        $this->get(route('blogs'))
            ->assertOk()
            ->assertSee('<title>Blog Digital Marketing, Performance &amp; Data | Thương hiệu thử nghiệm</title>', false)
            ->assertSee('<meta name="description" content="Góc chia sẻ về Digital Marketing, Performance Marketing, dữ liệu và đào tạo: tư duy, cách triển khai và bài học từ thực tế.">', false)
            ->assertSee('<meta property="og:image" content="http://localhost/storage/images/og.jpg">', false)
            ->assertSee('<link rel="canonical" href="http://localhost/blog">', false)
            ->assertSee('Digital Marketing, Performance &amp; Data', false)
            ->assertSee('Bài viết công khai')
            ->assertSee('Nội dung fallback từ bài viết.')
            ->assertSee('Performance')
            ->assertDontSee('<img src="http://localhost/app/assets/images/others/thumb-16.jpg" alt="Bài viết công khai"', false)
            ->assertDontSee('Bài viết nháp')
            ->assertDontSee('Chủ đề nháp');
    }

    public function test_blog_filter_uses_valid_category_and_search_canonicalizes_to_root(): void
    {
        $this->createSiteIdentity();
        $category = Category::create(['name' => 'Dữ liệu', 'slug' => 'du-lieu']);
        $blog = $this->createBlog('Đọc dữ liệu marketing', 'doc-du-lieu-marketing');
        $blog->categories()->attach($category);

        $this->get(route('blogs', ['category' => $category->slug]))
            ->assertOk()
            ->assertSee('Bài viết về Dữ liệu')
            ->assertSee('<link rel="canonical" href="http://localhost/blog?category=du-lieu">', false)
            ->assertSee('<option value="du-lieu" selected>', false);

        $this->get(route('blogs', ['search' => 'dữ liệu', 'category' => $category->slug]))
            ->assertOk()
            ->assertSee('Kết quả tìm kiếm cho “dữ liệu”')
            ->assertSee('<link rel="canonical" href="http://localhost/blog">', false)
            ->assertSee('Xóa bộ lọc');

        $this->get(route('blogs', ['category' => 'khong-ton-tai']))
            ->assertOk()
            ->assertSee('Kết quả lọc bài viết')
            ->assertSee('<link rel="canonical" href="http://localhost/blog">', false)
            ->assertSee('Xem tất cả bài viết');
    }

    public function test_blog_category_pagination_keeps_its_canonical_url(): void
    {
        $this->createSiteIdentity();
        $category = Category::create(['name' => 'Digital Marketing', 'slug' => 'digital-marketing']);

        foreach (range(1, 13) as $number) {
            $blog = $this->createBlog("Bài {$number}", "bai-{$number}");
            $blog->categories()->attach($category);
        }

        $this->get(route('blogs', ['category' => $category->slug, 'page' => 2]))
            ->assertOk()
            ->assertSee('<link rel="canonical" href="http://localhost/blog?category=digital-marketing&amp;page=2">', false)
            ->assertSee('Bài viết về Digital Marketing');
    }

    public function test_blog_out_of_range_page_does_not_create_an_indexable_page_url(): void
    {
        $this->createSiteIdentity();
        $category = Category::create(['name' => 'Data', 'slug' => 'data']);
        $blog = $this->createBlog('Bài Data', 'bai-data');
        $blog->categories()->attach($category);

        $this->get(route('blogs', ['category' => $category->slug, 'page' => 99]))
            ->assertOk()
            ->assertSee('<link rel="canonical" href="http://localhost/blog?category=data">', false)
            ->assertSee('Chưa có bài viết trong mục này.');
    }

    public function test_blog_ignores_non_scalar_filter_queries(): void
    {
        $this->createSiteIdentity();

        $this->get('/blog?search%5B%5D=data&category%5B%5D=data')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="http://localhost/blog">', false)
            ->assertDontSee('Xóa bộ lọc');
    }

    public function test_blog_treats_zero_as_a_real_search_query(): void
    {
        $this->createSiteIdentity();
        $this->createBlog('Bài viết khác', 'bai-viet-khac');

        $this->get(route('blogs', ['search' => '0']))
            ->assertOk()
            ->assertSee('Kết quả tìm kiếm cho “0”')
            ->assertSee('Chưa có bài viết trong mục này.')
            ->assertDontSee('Bài viết khác');
    }

    public function test_blog_search_finds_content_highlights_keywords_and_supports_sorting(): void
    {
        $this->createSiteIdentity();
        $this->createBlog('Báo cáo tuần', 'bao-cao-tuan', true, 'Tóm tắt ngắn.', '<p>Hướng dẫn tìm insight từ dữ liệu thực tế.</p>');
        $this->createBlog('Bài viết không liên quan', 'bai-viet-khong-lien-quan');

        $this->get(route('blogs', ['search' => 'insight', 'sort' => 'relevance']))
            ->assertOk()
            ->assertSee('1 bài viết · Liên quan nhất')
            ->assertSee('<mark>insight</mark>', false)
            ->assertSee('Báo cáo tuần')
            ->assertDontSee('Bài viết không liên quan');

        $this->get(route('blogs', ['sort' => 'relevance']))
            ->assertOk()
            ->assertSee('2 bài viết · Mới nhất')
            ->assertSee('<option value="latest" selected>', false);
    }

    private function createSiteIdentity(): void
    {
        Setting::create([
            'name' => 'Thương hiệu thử nghiệm',
            'url' => 'https://thuonghieu-thu-nghiem.test',
            'desc_seo' => 'Mô tả site.',
            'og_image' => '/storage/images/og.jpg',
            'favicon' => '/storage/images/favicon.jpg',
        ]);
    }

    private function createBlog(string $title, string $slug, bool $published = true, ?string $description = 'Mô tả bài viết.', ?string $content = 'Nội dung bài viết.'): Blog
    {
        return Blog::create([
            'title' => $title,
            'slug' => $slug,
            'description' => $description,
            'content' => $content,
            'is_published' => $published,
        ]);
    }
}
