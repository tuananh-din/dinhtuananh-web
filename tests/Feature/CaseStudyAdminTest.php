<?php

namespace Tests\Feature;

use App\Models\CaseStudy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CaseStudyAdminTest extends TestCase
{
    use RefreshDatabase;

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
}
