<?php

namespace Tests\Feature;

use App\Models\CaseStudy;
use App\Models\CaseStudyImage;
use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CaseStudyAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
    }

    public function test_admin_can_create_update_and_delete_a_case_study(): void
    {
        $user = User::factory()->create();
        $payload = [
            'title' => 'Chiến dịch ra mắt sản phẩm',
            'summary' => 'Tăng trưởng doanh thu có thể đo lường.',
            'client' => 'Khách hàng A',
            'industry' => 'Thương mại điện tử',
            'platforms' => 'Facebook Ads',
            'duration' => '3 tháng',
            'role' => 'Chiến lược',
            'kpi_1_label' => 'ROAS',
            'kpi_1_value' => '4.2x',
            'content' => '<h2>Kết quả</h2><p>Nội dung.</p>',
            'is_published' => 1,
        ];

        $this->actingAs($user)->post(route('case-study.store'), $payload)->assertSessionHasNoErrors();
        $caseStudy = CaseStudy::firstOrFail();
        $this->assertDatabaseHas('case_studies', ['id' => $caseStudy->id, 'slug' => 'chien-dich-ra-mat-san-pham', 'is_published' => 1]);

        $this->actingAs($user)->post(route('case-study.store'), array_merge($payload, [
            'id' => $caseStudy->id,
            'slug' => 'ket-qua-moi',
            'summary' => 'Kết quả đã cập nhật.',
        ]))->assertSessionHasNoErrors();
        $this->assertDatabaseHas('case_studies', ['id' => $caseStudy->id, 'slug' => 'ket-qua-moi', 'summary' => 'Kết quả đã cập nhật.']);

        $this->actingAs($user)->post(route('case-study.delete', $caseStudy->id))->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('case_studies', ['id' => $caseStudy->id]);
    }

    public function test_case_study_slug_is_generated_and_gets_a_suffix_when_duplicated(): void
    {
        $user = User::factory()->create();
        CaseStudy::create(['title' => 'Có sẵn', 'slug' => 'ket-qua-tot', 'content' => '<p>Nội dung</p>']);

        $this->actingAs($user)->post(route('case-study.store'), [
            'title' => 'Tiêu đề tự sinh', 'content' => '<p>Nội dung</p>',
        ])->assertSessionHasNoErrors();
        $this->actingAs($user)->post(route('case-study.store'), [
            'title' => 'Tiêu đề khác', 'slug' => 'Kết quả tốt!', 'content' => '<p>Nội dung</p>',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('case_studies', ['slug' => 'tieu-de-tu-sinh']);
        $this->assertDatabaseHas('case_studies', ['slug' => 'ket-qua-tot-1']);
    }

    public function test_admin_can_preview_a_draft_case_study(): void
    {
        $caseStudy = CaseStudy::create(['title' => 'Bản nháp', 'slug' => 'ban-nhap', 'content' => '<p>Nội dung</p>', 'is_published' => false]);

        $this->actingAs(User::factory()->create())
            ->get(route('case-study.preview', $caseStudy->id))
            ->assertOk()
            ->assertSee('Bản xem trước');
    }

    public function test_published_case_study_has_a_public_detail_page_and_draft_is_hidden(): void
    {
        $published = CaseStudy::create([
            'title' => 'Kết quả công khai',
            'slug' => 'ket-qua-cong-khai',
            'summary' => 'Tóm tắt kết quả.',
            'industry' => 'Bán lẻ',
            'content' => '<p>Nội dung công khai.</p>',
            'is_published' => true,
        ]);
        $draft = CaseStudy::create([
            'title' => 'Bản nháp riêng tư',
            'slug' => 'ban-nhap-rieng-tu',
            'content' => '<p>Nội dung nháp.</p>',
            'is_published' => false,
        ]);

        $this->get(route('portfolio.detail', $published->slug))->assertOk()->assertSee('Tóm tắt kết quả.');
        $this->get(route('portfolio.detail', $draft->slug))->assertNotFound();
        $this->get(route('portfolio'))->assertOk()->assertSee('Kết quả công khai');
    }

    public function test_portfolio_and_home_keep_the_legacy_image_fallback_without_published_case_studies(): void
    {
        Image::create([
            'type' => 0,
            'title' => 'Dự án ảnh cũ',
            'image' => 'app/assets/images/others/thumb-16.jpg',
            'description' => 'Mô tả cũ',
        ]);
        CaseStudy::create([
            'title' => 'Chỉ là nháp',
            'slug' => 'chi-la-nhap',
            'content' => '<p>Nội dung nháp.</p>',
            'is_published' => false,
        ]);

        $this->get(route('portfolio'))->assertOk()->assertSee('Dự án ảnh cũ');
        $this->get(route('index'))->assertOk()->assertSee('Dự án ảnh cũ');
    }

    public function test_admin_can_upload_update_and_delete_individual_gallery_images(): void
    {
        $caseStudy = CaseStudy::create([
            'title' => 'Gallery Case Study',
            'slug' => 'gallery-case-study',
            'content' => '<p>Noi dung</p>',
        ]);
        $user = User::factory()->create();
        $payload = [
            'id' => $caseStudy->id,
            'title' => $caseStudy->title,
            'slug' => $caseStudy->slug,
            'content' => $caseStudy->content,
            'gallery_images' => [
                UploadedFile::fake()->image('proof-one.jpg'),
                UploadedFile::fake()->image('proof-two.jpg'),
            ],
        ];

        $this->actingAs($user)->post(route('case-study.store'), $payload)->assertSessionHasNoErrors();

        $images = $caseStudy->fresh()->images;
        $this->assertCount(2, $images);
        $first = $images->first();
        $second = $images->last();
        Storage::assertExists(str_replace('/storage/', 'public/', $first->image));
        Storage::assertExists(str_replace('/storage/', 'public/', $second->image));

        $this->actingAs($user)->post(route('case-study.store'), [
            'id' => $caseStudy->id,
            'title' => $caseStudy->title,
            'slug' => $caseStudy->slug,
            'content' => $caseStudy->content,
            'gallery' => [
                $first->id => ['caption' => 'Anh sau', 'sort_order' => 20],
                $second->id => ['caption' => 'Anh truoc', 'sort_order' => 10],
            ],
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('case_study_images', ['id' => $first->id, 'caption' => 'Anh sau', 'sort_order' => 20]);
        $this->assertDatabaseHas('case_study_images', ['id' => $second->id, 'caption' => 'Anh truoc', 'sort_order' => 10]);

        $this->actingAs($user)->post(route('case-study.image.delete', $first->id))->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('case_study_images', ['id' => $first->id]);
        $this->assertDatabaseHas('case_study_images', ['id' => $second->id]);
        Storage::assertMissing(str_replace('/storage/', 'public/', $first->image));
        Storage::assertExists(str_replace('/storage/', 'public/', $second->image));
    }

    public function test_public_detail_renders_gallery_in_sort_order_and_hides_it_when_empty(): void
    {
        $withGallery = CaseStudy::create([
            'title' => 'Case co gallery',
            'slug' => 'case-co-gallery',
            'content' => '<p>Noi dung</p>',
            'is_published' => true,
        ]);
        CaseStudyImage::create(['case_study_id' => $withGallery->id, 'image' => '/storage/images/late.jpg', 'caption' => 'Anh sau', 'sort_order' => 20]);
        CaseStudyImage::create(['case_study_id' => $withGallery->id, 'image' => '/storage/images/early.jpg', 'caption' => 'Anh truoc', 'sort_order' => 10]);

        $response = $this->get(route('portfolio.detail', $withGallery->slug))->assertOk()->assertSee('Hình ảnh minh chứng');
        $this->assertLessThan(
            strpos($response->getContent(), 'Anh sau'),
            strpos($response->getContent(), 'Anh truoc')
        );

        $withoutGallery = CaseStudy::create([
            'title' => 'Case khong gallery',
            'slug' => 'case-khong-gallery',
            'content' => '<p>Noi dung</p>',
            'is_published' => true,
        ]);

        $this->get(route('portfolio.detail', $withoutGallery->slug))->assertOk()->assertDontSee('Hình ảnh minh chứng');
    }
}
