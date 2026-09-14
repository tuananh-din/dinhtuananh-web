<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotFoundPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_not_found_page_offers_navigation_and_blog_search(): void
    {
        $this->withViewErrors([])
            ->view('errors.404', ['infor' => ['name' => 'Đinh Tuấn Anh']])
            ->assertSee('Không tìm thấy trang')
            ->assertSee('Trang bạn tìm không tồn tại hoặc đã được di chuyển.')
            ->assertSee('name="search"', false)
            ->assertSee(route('contact'), false)
            ->assertSee('<meta name="robots" content="noindex,nofollow">', false);
    }
}
