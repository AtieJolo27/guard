<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_violations_requires_login(): void
    {
        $this->get('/violations')->assertRedirect('/login');
    }

    public function test_login_page_is_available(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_logout_clears_guard_session(): void
    {
        $this->withSession(['guard_id' => 1, 'guard_name' => 'Test Guard'])
            ->get('/guard/logout')
            ->assertRedirect('/login')
            ->assertSessionMissing('guard_id')
            ->assertSessionMissing('guard_name');
    }
}
