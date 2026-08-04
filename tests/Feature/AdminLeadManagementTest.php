<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLeadManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_search_leads_and_export_current_filter(): void
    {
        $user = User::factory()->create();
        Lead::create(['name' => 'Nguyen Van A', 'phone' => '0900000000', 'email' => 'a@example.test', 'status' => 'new']);
        Lead::create(['name' => 'Tran Van B', 'phone' => '0900000001', 'email' => 'b@example.test', 'status' => 'won']);

        $this->actingAs($user)->get(route('admin.lead', ['search' => 'a@example.test', 'status' => 'new']))
            ->assertOk()
            ->assertSee('Nguyen Van A')
            ->assertDontSee('Tran Van B');

        $this->actingAs($user)->get(route('lead.export', ['search' => 'a@example.test', 'status' => 'new']))
            ->assertOk()
            ->assertDownload('leads.csv');
    }

    public function test_admin_can_soft_delete_a_lead(): void
    {
        $user = User::factory()->create();
        $lead = Lead::create(['name' => 'Nguyen Van A', 'phone' => '0900000000', 'status' => 'new']);

        $this->actingAs($user)->post(route('lead.delete', $lead))->assertSessionHas('success');

        $this->assertSoftDeleted('leads', ['id' => $lead->id]);
        $this->actingAs($user)->get(route('admin.lead'))->assertDontSee('Nguyen Van A');
    }
}
