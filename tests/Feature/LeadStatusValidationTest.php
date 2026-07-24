<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadStatusValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_a_lead_with_a_supported_status(): void
    {
        $user = User::factory()->create();
        $lead = Lead::create([
            'name' => 'Nguyen Van A',
            'phone' => '0900000000',
            'status' => 'new',
        ]);

        $response = $this->actingAs($user)->post(route('lead.update', $lead), [
            'status' => 'contacted',
            'note' => 'Da goi tu van.',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'status' => 'contacted',
            'note' => 'Da goi tu van.',
        ]);
    }

    public function test_admin_cannot_update_a_lead_with_an_unsupported_status(): void
    {
        $user = User::factory()->create();
        $lead = Lead::create([
            'name' => 'Nguyen Van B',
            'phone' => '0900000001',
            'status' => 'new',
        ]);

        $response = $this->from(route('admin.lead'))
            ->actingAs($user)
            ->post(route('lead.update', $lead), [
                'status' => 'invalid-status',
                'note' => 'Khong duoc luu.',
            ]);

        $response->assertRedirect(route('admin.lead'));
        $response->assertSessionHasErrors('status');
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'status' => 'new',
            'note' => null,
        ]);
    }
}
