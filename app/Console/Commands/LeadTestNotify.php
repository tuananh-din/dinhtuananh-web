<?php

namespace App\Console\Commands;

use App\Mail\NewLeadNotification;
use App\Models\Lead;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class LeadTestNotify extends Command
{
    protected $signature = 'lead:test-notify {email? : Email nhận thông báo; mặc định là mail.lead_notify}';

    protected $description = 'Gửi email thông báo lead mẫu để kiểm tra mailer mà không lưu lead vào database';

    public function handle(): int
    {
        $recipient = $this->argument('email') ?: config('mail.lead_notify');

        if (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            $this->error('Email nhận không hợp lệ. Truyền email vào command hoặc cấu hình LEAD_NOTIFY_EMAIL.');

            return self::FAILURE;
        }

        $mailer = config('mail.default');
        $host = config("mail.mailers.{$mailer}.host") ?: '(không áp dụng)';
        $lead = new Lead([
            'id' => 0,
            'name' => 'Lead test (không lưu DB)',
            'phone' => '0900000000',
            'email' => 'lead-test@example.test',
            'message' => 'Đây là email kiểm tra cấu hình thông báo lead.',
            'source_page' => 'artisan:lead:test-notify',
            'status' => 'new',
        ]);
        $lead->created_at = now();

        $this->line("Mailer: {$mailer}");
        $this->line("Host: {$host}");
        $this->line("Recipient: {$recipient}");

        try {
            Mail::to($recipient)->send(new NewLeadNotification($lead));
        } catch (\Throwable $e) {
            $this->error('Gửi email test thất bại: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->info('Đã gửi email test. Lead mẫu không được lưu vào database.');

        return self::SUCCESS;
    }
}
