<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Category;
use App\Models\LeadMagnet;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class GroupEightCoverageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_edit_and_delete_category_without_deleting_blog(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('category.store'), ['name' => 'Marketing', 'slug' => 'marketing'])->assertRedirect(route('admin.category'));
        $category = Category::firstOrFail();
        $blog = Blog::create(['title' => 'Bài', 'slug' => 'bai', 'content' => 'Nội dung', 'is_published' => true]);
        $blog->categories()->attach($category);

        $this->actingAs($user)->post(route('category.store'), ['id' => $category->id, 'name' => 'Marketing mới', 'slug' => 'marketing'])->assertRedirect(route('admin.category'));
        $this->actingAs($user)->post(route('category.delete', $category->id))->assertRedirect();

        $this->assertDatabaseHas('blogs', ['id' => $blog->id]);
        $this->assertDatabaseMissing('blog_category', ['blog_id' => $blog->id, 'category_id' => $category->id]);
    }

    public function test_lead_magnet_subscribe_honeypot_and_source_are_covered(): void
    {
        Mail::fake();
        $magnet = LeadMagnet::create(['name' => 'Tài liệu', 'file_path' => 'lead-magnets/test.pdf', 'is_active' => true]);

        $this->post(route('lead-magnet.subscribe', $magnet->id), ['email' => 'bot@example.test', 'website' => 'spam'])
            ->assertRedirect(route('thank.you'));
        $this->assertDatabaseMissing('subscribers', ['email' => 'bot@example.test']);

        $this->post(route('lead-magnet.subscribe', $magnet->id), ['email' => 'reader@example.test'])
            ->assertRedirect(route('thank.you'));
        $this->assertDatabaseHas('subscribers', ['email' => 'reader@example.test', 'source' => 'lead_magnet']);
    }

    public function test_guest_is_blocked_from_new_admin_routes(): void
    {
        foreach ([route('admin.category'), route('admin.lead-magnet'), route('admin.subscriber')] as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }
    }

    public function test_backup_command_fails_cleanly_with_missing_configuration(): void
    {
        config()->set('database.connections.mysql.host', null);

        $this->artisan('backup:db')->assertExitCode(1);
    }
}
