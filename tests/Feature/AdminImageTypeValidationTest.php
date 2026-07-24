<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminImageTypeValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_create_an_image_with_an_unsupported_type(): void
    {
        $user = User::factory()->create();

        $response = $this->from(route('image.create'))
            ->actingAs($user)
            ->post(route('image.store'), [
                'title' => 'Ảnh không hợp lệ',
                'type' => 2,
            ]);

        $response->assertRedirect(route('image.create'));
        $response->assertSessionHasErrors('type');
        $this->assertDatabaseCount('images', 0);
    }

    public function test_admin_can_create_an_image_with_a_supported_type(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('image.store'), [
            'title' => 'Ảnh giới thiệu',
            'type' => 1,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('images', [
            'title' => 'Ảnh giới thiệu',
            'type' => 1,
        ]);
    }
}
