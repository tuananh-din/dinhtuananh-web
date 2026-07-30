<?php

namespace Tests\Feature;

use App\Mail\NewLeadNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class LeadNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_lead_submission_sends_notification_to_configured_recipient_with_lead_data_and_reply_to(): void
    {
        config(['mail.lead_notify' => 'notify@example.test']);
        Mail::fake();

        $this->post(route('lead.store'), [
            'name' => 'Nguyễn Thử Nghiệm',
            'phone' => '0900000000',
            'email' => 'customer@example.test',
            'message' => 'Cần được tư vấn.',
            'source_page' => 'contact',
        ])->assertSessionHas('success');

        Mail::assertSent(NewLeadNotification::class, function (NewLeadNotification $mail) {
            $mail->build();

            return $mail->hasTo('notify@example.test')
                && $mail->lead->name === 'Nguyễn Thử Nghiệm'
                && $mail->lead->phone === '0900000000'
                && $mail->lead->email === 'customer@example.test'
                && $mail->lead->message === 'Cần được tư vấn.'
                && $mail->lead->source_page === 'contact'
                && collect($mail->replyTo)->contains('address', 'customer@example.test');
        });
    }

    public function test_lead_submission_without_email_sends_notification_without_reply_to(): void
    {
        config(['mail.lead_notify' => 'notify@example.test']);
        Mail::fake();

        $this->post(route('lead.store'), [
            'name' => 'Khách không email',
            'phone' => '0900000001',
        ])->assertSessionHas('success');

        Mail::assertSent(NewLeadNotification::class, function (NewLeadNotification $mail) {
            $mail->build();

            return $mail->hasTo('notify@example.test')
                && empty($mail->lead->email)
                && empty($mail->replyTo);
        });
    }
}
