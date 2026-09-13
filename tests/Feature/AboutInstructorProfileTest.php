<?php

namespace Tests\Feature;

use App\Models\About;
use App\Models\Blog;
use App\Models\CaseStudy;
use App\Models\Course;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutInstructorProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_only_shows_public_learning_content(): void
    {
        About::create([
            'name' => 'Tuấn Anh',
            'description' => 'Nội dung giới thiệu đã được xác minh.',
            'about_me' => '<p>Câu chuyện nghề nghiệp đã được duyệt.</p>',
        ]);
        Course::create(['title' => 'Khóa học công khai', 'slug' => 'khoa-hoc-cong-khai', 'is_active' => true]);
        Course::create(['title' => 'Khóa học nháp', 'slug' => 'khoa-hoc-nhap', 'is_active' => false]);
        CaseStudy::create(['title' => 'Case công khai', 'slug' => 'case-cong-khai', 'is_published' => true]);
        CaseStudy::create(['title' => 'Case nháp', 'slug' => 'case-nhap', 'is_published' => false]);
        Blog::create(['title' => 'Bài viết công khai', 'slug' => 'bai-viet-cong-khai', 'is_published' => true]);
        Blog::create(['title' => 'Bài viết nháp', 'slug' => 'bai-viet-nhap', 'is_published' => false]);
        Testimonial::create(['name' => 'Người chia sẻ', 'content' => 'Nội dung được công bố.', 'is_active' => true]);
        Testimonial::create(['name' => 'Không công khai', 'content' => 'Không được hiển thị.', 'is_active' => false]);

        $response = $this->get(route('about'));

        $response->assertOk()
            ->assertSee('Học Digital Marketing từ tư duy triển khai, không chỉ từ lý thuyết.')
            ->assertSee('Khóa học công khai')->assertDontSee('Khóa học nháp')
            ->assertSee('Case công khai')->assertDontSee('Case nháp')
            ->assertSee('Bài viết công khai')->assertDontSee('Bài viết nháp')
            ->assertDontSee('Người chia sẻ')->assertDontSee('Không công khai')
            ->assertDontSee('Những chia sẻ được hiển thị trên website')
            ->assertDontSee('Cách học tại đây')
            ->assertDontSee('role="progressbar"', false)
            ->assertDontSee('about-skill-progress', false);
    }

    public function test_about_uses_helpful_empty_state_without_public_proof(): void
    {
        About::create(['name' => 'Tuấn Anh']);

        $response = $this->get(route('about'));

        $response->assertOk()
            ->assertSee('Hiện chưa có khóa học để hiển thị.')
            ->assertSee(route('contact'), false)
            ->assertDontSee('Tìm hiểu trước khi quyết định')
            ->assertDontSee('Những chia sẻ được hiển thị trên website');
    }
}
