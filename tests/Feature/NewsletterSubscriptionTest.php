<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class NewsletterSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_subscribe_and_brevo_failure_does_not_block_database_save(): void
    {
        config(['services.brevo.api_key' => 'test-key', 'services.brevo.list_id' => 1]);
        Http::fake(['https://api.brevo.com/*' => Http::response([], 500)]);

        $this->post(route('newsletter.store'), ['email' => 'newsletter@example.test', 'source' => 'footer'])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('subscribers', ['email' => 'newsletter@example.test', 'source' => 'footer']);
    }

    public function test_duplicate_email_is_handled_gracefully(): void
    {
        $this->post(route('newsletter.store'), ['email' => 'newsletter@example.test']);

        $this->post(route('newsletter.store'), ['email' => 'newsletter@example.test'])
            ->assertSessionHas('success');

        $this->assertDatabaseCount('subscribers', 1);
    }
}
