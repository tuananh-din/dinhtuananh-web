<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_visiting_an_admin_route(): void
    {
        $response = $this->get(route('course.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_visit_an_admin_route(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('course.create'));

        $response->assertOk();
    }
}
