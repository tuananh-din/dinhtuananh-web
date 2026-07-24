<?php

namespace Tests\Feature;

use App\Mail\NewLeadNotification;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PublicLeadSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_submit_a_valid_lead_for_a_course(): void
    {
        Mail::fake();
        $course = Course::create([
            'title' => 'Facebook Ads',
            'slug' => 'facebook-ads',
            'short_description' => 'Khóa học Facebook Ads.',
        ]);

        $response = $this->post(route('lead.store'), [
            'course_id' => $course->id,
            'name' => 'Nguyen Van A',
            'phone' => '0900000000',
            'email' => 'nguyen@example.com',
            'message' => 'Can tu van khoa hoc.',
            'source_page' => 'course_detail',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('leads', [
            'course_id' => $course->id,
            'name' => 'Nguyen Van A',
            'phone' => '0900000000',
            'email' => 'nguyen@example.com',
            'message' => 'Can tu van khoa hoc.',
            'source_page' => 'course_detail',
            'status' => 'new',
        ]);
        Mail::assertSent(NewLeadNotification::class);
    }

    public function test_honeypot_submission_does_not_create_a_lead_or_send_mail(): void
    {
        Mail::fake();

        $response = $this->post(route('lead.store'), [
            'name' => 'Bot User',
            'phone' => '0900000001',
            'website' => 'https://spam.example.com',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('leads', [
            'name' => 'Bot User',
        ]);
        Mail::assertNothingSent();
    }

    public function test_lead_submission_requires_name_and_phone(): void
    {
        Mail::fake();

        $response = $this->from(route('contact'))->post(route('lead.store'), [
            'email' => 'nguyen@example.com',
        ]);

        $response->assertRedirect(route('contact'));
        $response->assertSessionHasErrors(['name', 'phone']);
        $this->assertDatabaseCount('leads', 0);
        Mail::assertNothingSent();
    }
}
