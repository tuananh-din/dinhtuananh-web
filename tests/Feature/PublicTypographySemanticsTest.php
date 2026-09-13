<?php

namespace Tests\Feature;

use App\Models\About;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicTypographySemanticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_uses_non_heading_eyebrows_for_section_labels(): void
    {
        $this->createSiteIdentity();

        $this->get(route('index'))
            ->assertOk()
            ->assertSee('<p class="type-eyebrow">Bắt đầu theo mục tiêu của bạn</p>', false)
            ->assertDontSee('<h6>Bắt đầu theo mục tiêu của bạn</h6>', false);
    }

    public function test_courses_have_a_page_title_instead_of_a_heading_eyebrow(): void
    {
        $this->createSiteIdentity();

        $this->get(route('courses'))
            ->assertOk()
            ->assertSee('<p class="type-eyebrow">Kh&#243;a h&#7885;c</p>', false)
            ->assertSee('<h1>Danh s&#225;ch kh&#243;a h&#7885;c &#273;ang m&#7903; &#273;&#259;ng k&#253;</h1>', false)
            ->assertDontSee('<h6>Kh&#243;a h&#7885;c</h6>', false);
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
