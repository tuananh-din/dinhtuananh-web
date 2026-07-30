<?php

namespace Tests\Feature;

use App\Mail\NewLeadNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class LeadTestNotifyCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_a_sample_notification_without_saving_a_lead(): void
    {
        Mail::fake();
        $mailer = config('mail.default');
        $host = config("mail.mailers.{$mailer}.host") ?: '(không áp dụng)';

        $this->artisan('lead:test-notify qa@example.test')
            ->expectsOutput("Mailer: {$mailer}")
            ->expectsOutput("Host: {$host}")
            ->expectsOutput('Recipient: qa@example.test')
            ->assertSuccessful();

        $this->assertDatabaseCount('leads', 0);
        Mail::assertSent(NewLeadNotification::class, function (NewLeadNotification $mail) {
            return $mail->hasTo('qa@example.test')
                && $mail->lead->exists === false
                && $mail->lead->name === 'Lead test (không lưu DB)';
        });
    }
}
