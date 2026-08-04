<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class NewsletterTestBrevo extends Command
{
    protected $signature = 'newsletter:test-brevo {email? : Email test, mặc định là newsletter-test@example.test}';
    protected $description = 'Kiểm tra kết nối Brevo bằng cách thêm hoặc cập nhật contact test';

    public function handle(): int
    {
        $apiKey = config('services.brevo.api_key');
        $listId = config('services.brevo.list_id');
        $email = $this->argument('email') ?: 'newsletter-test@example.test';

        if (!$apiKey || !$listId) {
            $this->error('Thiếu BREVO_API_KEY hoặc BREVO_LIST_ID.');
            return self::FAILURE;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Email không hợp lệ.');
            return self::FAILURE;
        }

        try {
            Http::acceptJson()->withHeaders(['api-key' => $apiKey])
                ->post('https://api.brevo.com/v3/contacts', [
                    'email' => $email,
                    'listIds' => [(int) $listId],
                    'updateEnabled' => true,
                ])->throw();
        } catch (\Throwable $e) {
            $this->error('Kết nối Brevo thất bại: '.$e->getMessage());
            return self::FAILURE;
        }

        $this->info('Kết nối Brevo thành công.');
        return self::SUCCESS;
    }
}
