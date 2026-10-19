<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_sees_sign_in_gate_instead_of_portfolio_form(): void
    {
        $this->get(route('portfolios.create'))
            ->assertOk()
            ->assertSee('Sign in before you fill out or submit your portfolio.')
            ->assertSee(route('login'))
            ->assertSee(route('register'))
            ->assertDontSee('Start with you');
    }

    public function test_guest_navigation_places_login_before_new_portfolio_without_registration_link(): void
    {
        $response = $this->get(route('home'))
            ->assertOk()
            ->assertSee('New portfolio')
            ->assertSee('Log in')
            ->assertDontSee('Register');

        $content = $response->getContent();
        $this->assertLessThan(strpos($content, 'New portfolio'), strpos($content, 'Log in'));

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('aria-current="page"', false)
            ->assertSee('Create one');
    }

    public function test_homepage_displays_all_three_template_previews_below_start_action(): void
    {
        $response = $this->get(route('home'))
            ->assertOk()
            ->assertSee('Start your portfolio')
            ->assertSee('Explore the templates')
            ->assertSee('Simple')
            ->assertSee('Modern')
            ->assertSee('Creative');

        $content = $response->getContent();
        $this->assertLessThan(strpos($content, 'id="template-showcase-title"'), strpos($content, 'Start your portfolio'));
        $this->assertLessThan(strpos($content, 'id="workspace-title"'), strpos($content, 'id="template-showcase-title"'));
    }

    public function test_homepage_explains_portfolio_creation_steps_below_templates(): void
    {
        $response = $this->get(route('home'))
            ->assertOk()
            ->assertSee('How it works')
            ->assertSee('Create an account or sign in')
            ->assertSee('Add your details')
            ->assertSee('Choose a look and share');

        $content = $response->getContent();
        $this->assertLessThan(strpos($content, 'id="process-title"'), strpos($content, 'id="template-showcase-title"'));
        $this->assertLessThan(strpos($content, 'id="workspace-title"'), strpos($content, 'id="process-title"'));
    }

    public function test_registration_page_is_available_to_guests(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Create your account')
            ->assertSee('Confirm password');
    }

    public function test_registration_creates_and_authenticates_user_then_redirects_home(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Jordan Lee',
            'email' => 'jordan@example.com',
            'password' => 'secure-password',
            'password_confirmation' => 'secure-password',
        ]);

        $response->assertRedirect(route('home'));

        $user = User::where('email', 'jordan@example.com')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertModelExists($user);
    }

    public function test_guest_cannot_submit_portfolio(): void
    {
        $this->post(route('portfolios.store'), [
            'name' => 'Jordan Lee',
            'email' => 'jordan@example.com',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseCount('portfolios', 0);
    }

    public function test_login_returns_user_to_portfolio_form(): void
    {
        $user = User::factory()->create(['email' => 'jordan@example.com']);

        $this->get(route('portfolios.create'))->assertOk();

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('portfolios.create'));

        $this->assertAuthenticatedAs($user);
        $this->get(route('portfolios.create'))->assertOk()->assertSee('Start with you');
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        $user = User::factory()->create(['email' => 'jordan@example.com']);

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => $user->email,
                'password' => 'incorrect-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_logout_ends_authenticated_session(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
