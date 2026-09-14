<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InlineInputValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_forms_include_blur_only_inline_error_behavior(): void
    {
        $this->get(route('contact'))
            ->assertOk()
            ->assertSee("field.addEventListener('blur'", false)
            ->assertSee("field.addEventListener('focus'", false)
            ->assertSee('form-field-error--client', false)
            ->assertSee('fa-circle-exclamation', false)
            ->assertSee('field.validity.valid', false);
    }
}
