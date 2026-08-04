<?php

namespace Tests\Feature;

use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSubscriberManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_view_subscriber_list(): void
    {
        $this->get(route('admin.subscriber'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_and_search_subscribers(): void
    {
        $user = User::factory()->create();
        Subscriber::create(['email' => 'footer@example.test', 'source' => 'footer']);
        Subscriber::create(['email' => 'magnet@example.test', 'source' => 'lead_magnet']);

        $this->actingAs($user)->get(route('admin.subscriber', ['search' => 'footer@', 'source' => 'footer']))
            ->assertOk()->assertSee('footer@example.test')->assertDontSee('magnet@example.test');
    }
}
