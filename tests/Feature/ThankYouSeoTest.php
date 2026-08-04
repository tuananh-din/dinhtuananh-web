<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThankYouSeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_thank_you_page_is_noindex(): void
    {
        $this->get(route('thank.you'))
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex,nofollow">', false);
    }
}
