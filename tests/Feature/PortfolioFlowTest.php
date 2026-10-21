<?php

namespace Tests\Feature;

use App\Models\Portfolio;
use App\Models\User;
use App\Services\PortfolioPhotoStorage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
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
            ->assertSee('no app-side size limit')
            ->assertSee('Create portfolio')
            ->assertDontSee('Step 1 of 3');
    }

    public function test_profile_picture_larger_than_four_megabytes_is_saved_and_visible(): void
    {
        Storage::fake('public');
        config([
            'services.supabase.url' => null,
            'services.supabase.service_key' => null,
            'services.supabase.storage_bucket' => null,
        ]);

        $this->actingAs(User::factory()->create());

        $this->post(route('portfolios.store'), [
            'name' => 'Jordan Lee',
            'email' => 'jordan@example.com',
            'profile_picture' => UploadedFile::fake()
                ->createWithContent('portrait.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAADUlEQVR4nGP4z8AAAAMBAQDJ/pLvAAAAAElFTkSuQmCC'))
                ->size(4097),
        ])->assertRedirect();

        $portfolio = Portfolio::firstOrFail();
        $photoPath = 'profiles/'.basename((string) parse_url($portfolio->photo_url, PHP_URL_PATH));

        Storage::disk('public')->assertExists($photoPath);

        $key = session('portfolio_keys')[0];

        $this->put(route('portfolios.manage.template', $key), ['template' => 'modern'])
            ->assertRedirect(route('portfolios.manage.show', $key));

        $this->get(route('portfolios.public', $portfolio->slug))
            ->assertOk()
            ->assertSee('Portrait of Jordan Lee')
            ->assertSee($portfolio->photo_url, false);
    }

    public function test_non_image_profile_picture_is_rejected(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('portfolios.store'), [
            'name' => 'Jordan Lee',
            'email' => 'jordan@example.com',
            'profile_picture' => UploadedFile::fake()->create('document.pdf', 10, 'application/pdf'),
        ])->assertSessionHasErrors('profile_picture');

        $this->assertDatabaseCount('portfolios', 0);
    }

    public function test_supabase_profile_picture_upload_streams_the_original_image(): void
    {
        $imageContents = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAADUlEQVR4nGP4z8AAAAMBAQDJ/pLvAAAAAElFTkSuQmCC');
        $photo = UploadedFile::fake()->createWithContent('portrait.png', $imageContents);

        config([
            'services.supabase.url' => 'https://images.example.test',
            'services.supabase.service_key' => 'test-key',
            'services.supabase.storage_bucket' => 'portfolio-assets',
        ]);

        Http::preventStrayRequests();
        $uploadedContents = null;
        Http::fake([
            'https://images.example.test/storage/v1/object/portfolio-assets/*' => function (ClientRequest $request) use (&$uploadedContents) {
                $uploadedContents = $request->body();

                return Http::response('', 200);
            },
        ]);

        $photoUrl = app(PortfolioPhotoStorage::class)->store($photo);

        Http::assertSent(fn (ClientRequest $request): bool => $request->method() === 'POST');
        $this->assertSame($imageContents, $uploadedContents);
        $this->assertStringStartsWith(
            'https://images.example.test/storage/v1/object/public/portfolio-assets/profiles/',
            $photoUrl,
        );
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
