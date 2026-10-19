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
        $this->get('/')->assertOk();
    }

    public function test_assets_use_https_when_served_behind_https_proxy(): void
    {
        $this->withServerVariables(['HTTP_X_FORWARDED_PROTO' => 'https'])
            ->get('/')
            ->assertOk()
            ->assertSee('href="https://localhost:8000/build/assets/', false);
    }
}
