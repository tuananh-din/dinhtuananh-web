<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCourseCtaLinkValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_store_an_unsafe_course_cta_link(): void
    {
        $user = User::factory()->create();

        $response = $this->from(route('course.create'))
            ->actingAs($user)
            ->post(route('course.store'), $this->coursePayload([
                'cta_link' => 'javascript:alert(1)',
            ]));

        $response->assertRedirect(route('course.create'));
        $response->assertSessionHasErrors('cta_link');
        $this->assertDatabaseCount('courses', 0);
    }

    public function test_admin_can_store_supported_course_cta_link_types(): void
    {
        $user = User::factory()->create();

        foreach (['https://example.com/register', '/contact', 'tel:+84900000000'] as $index => $ctaLink) {
            $response = $this->actingAs($user)->post(route('course.store'), $this->coursePayload([
                'title' => 'Khóa học CTA '.$index,
                'cta_link' => $ctaLink,
            ]));

            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('courses', ['cta_link' => $ctaLink]);
        }
    }

    public function test_admin_cannot_store_course_metadata_that_exceeds_public_limits(): void
    {
        $user = User::factory()->create();

        $response = $this->from(route('course.create'))
            ->actingAs($user)
            ->post(route('course.store'), $this->coursePayload([
                'cta_text' => str_repeat('a', 61),
                'seo_title' => str_repeat('b', 61),
                'seo_description' => str_repeat('c', 156),
            ]));

        $response->assertRedirect(route('course.create'));
        $response->assertSessionHasErrors(['cta_text', 'seo_title', 'seo_description']);
        $this->assertDatabaseCount('courses', 0);
    }

    public function test_admin_can_store_course_metadata_at_public_limits(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('course.store'), $this->coursePayload([
            'cta_text' => str_repeat('a', 60),
            'seo_title' => str_repeat('b', 60),
            'seo_description' => str_repeat('c', 155),
        ]));

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('courses', [
            'cta_text' => str_repeat('a', 60),
            'seo_title' => str_repeat('b', 60),
            'seo_description' => str_repeat('c', 155),
        ]);
    }

    private function coursePayload(array $attributes = []): array
    {
        return array_merge([
            'title' => 'Khóa học CTA',
            'short_description' => 'Mô tả ngắn cho khóa học.',
            'is_active' => '1',
        ], $attributes);
    }
}
