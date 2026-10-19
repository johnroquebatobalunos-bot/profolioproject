<?php

namespace Tests\Feature;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_portfolio_creation_shows_all_sections_in_one_form(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('portfolios.create'))
            ->assertOk()
            ->assertSee('Start with you')
            ->assertSee("What you've learned", false)
            ->assertSee('Work worth sharing')
            ->assertSee('Create portfolio')
            ->assertDontSee('Step 1 of 3');
    }

    public function test_portfolio_can_be_created_edited_previewed_and_deleted(): void
    {
        $this->actingAs(User::factory()->create());

        $createResponse = $this->post(route('portfolios.store'), [
            'name' => 'Morgan Ellis',
            'email' => 'morgan@example.com',
            'phone' => '+1 555 0100',
            'address' => 'Portland, OR',
            'about' => 'I make useful things feel human.',
            'education_text' => 'B.A. in Design | West University | 2020-2024',
            'skills_text' => 'Research, Figma, Writing',
            'projects_text' => 'Field Notes | A digital archive for local stories | https://example.com/field-notes',
            'experience_text' => 'Designer | Studio North | 2023-now | Built a product language.',
            'links_text' => 'LinkedIn | https://linkedin.com/in/morgan',
        ]);

        $portfolio = Portfolio::firstOrFail();
        $key = session('portfolio_keys')[0];

        $createResponse->assertRedirect(route('portfolios.manage.templates', $key));

        $this->assertDatabaseHas('portfolios', [
            'id' => $portfolio->id,
            'name' => 'Morgan Ellis',
            'template' => 'simple',
        ]);

        $this->get(route('portfolios.manage.show', $key))
            ->assertOk()
            ->assertSee('PORTFOLIO OVERVIEW');

        $this->get(route('portfolios.manage.edit', $key))
            ->assertOk()
            ->assertSee('B.A. in Design | West University | 2020-2024');

        $this->put(route('portfolios.manage.update', $key), [
            'name' => 'Morgan Ellis Studio',
            'email' => 'morgan@example.com',
            'about' => 'Updated profile introduction.',
            'education_text' => 'B.A. in Design | West University | 2020-2024',
            'skills_text' => 'Research, Figma, Writing',
            'projects_text' => 'Field Notes | A digital archive for local stories | https://example.com/field-notes',
            'experience_text' => 'Designer | Studio North | 2023-now | Built a product language.',
            'links_text' => 'LinkedIn | https://linkedin.com/in/morgan',
        ])->assertRedirect(route('portfolios.manage.show', $key));

        $this->assertDatabaseHas('portfolios', ['id' => $portfolio->id, 'name' => 'Morgan Ellis Studio']);

        foreach (['simple', 'modern', 'creative'] as $template) {
            $this->put(route('portfolios.manage.template', $key), ['template' => $template])
                ->assertRedirect(route('portfolios.manage.show', $key));

            $this->get(route('portfolios.public', $portfolio->slug))->assertOk();
        }

        $this->put(route('portfolios.manage.template', $key), ['template' => 'unexpected'])
            ->assertSessionHasErrors('template');

        $this->delete(route('portfolios.manage.destroy', $key))
            ->assertRedirect(route('home'));

        $this->assertDatabaseMissing('portfolios', ['id' => $portfolio->id]);
        $this->get(route('portfolios.public', $portfolio->slug))->assertNotFound();
    }
}
