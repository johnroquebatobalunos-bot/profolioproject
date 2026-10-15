<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Services\PortfolioPhotoStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    private const TEMPLATES = [
        'simple' => 'Simple',
        'modern' => 'Modern',
        'creative' => 'Creative',
    ];

    public function index(): mixed
    {
        $hashes = collect(session('portfolio_keys', []))
            ->map(fn (string $key) => hash('sha256', $key))
            ->all();

        $portfolios = $hashes
            ? Portfolio::whereIn('management_key_hash', $hashes)->latest()->get()
            : collect();

        return view('home', compact('portfolios'));
    }

    public function create(): mixed
    {
        return view('portfolios.create');
    }

    public function store(Request $request, PortfolioPhotoStorage $photos): mixed
    {
        $data = $this->validatedInformation($request, true);
        $key = Str::random(64);

        $portfolio = Portfolio::create([
            ...$this->portfolioFields($data),
            'slug' => Str::slug($data['name']).'-'.Str::lower(Str::random(6)),
            'management_key_hash' => hash('sha256', $key),
            'template' => 'simple',
            'photo_url' => $photos->store($data['profile_picture'] ?? null),
        ]);

        $keys = session('portfolio_keys', []);
        $keys[] = $key;
        session(['portfolio_keys' => array_values(array_unique($keys))]);

        return redirect()->route('portfolios.manage.templates', $key)->with('status', 'Your portfolio is saved. Choose a look to continue.');
    }

    public function manage(string $key): mixed
    {
        $portfolio = $this->findManaged($key);

        return view('portfolios.manage', [
            'portfolio' => $portfolio,
            'templateName' => self::TEMPLATES[$portfolio->template],
            'publicUrl' => route('portfolios.public', $portfolio->slug),
        ]);
    }

    public function edit(string $key): mixed
    {
        $portfolio = $this->findManaged($key);

        return view('portfolios.edit', [
            'portfolio' => $portfolio,
            'key' => $key,
            'formValues' => $this->formValues($portfolio),
        ]);
    }

    public function update(Request $request, string $key, PortfolioPhotoStorage $photos): mixed
    {
        $portfolio = $this->findManaged($key);
        $data = $this->validatedInformation($request);
        $fields = $this->portfolioFields($data);

        if (isset($data['profile_picture'])) {
            $photos->delete($portfolio->photo_url);
            $fields['photo_url'] = $photos->store($data['profile_picture']);
        }

        $portfolio->update($fields);

        return redirect()->route('portfolios.manage.show', $key)->with('status', 'Your changes are saved.');
    }

    public function destroy(string $key, PortfolioPhotoStorage $photos): mixed
    {
        $portfolio = $this->findManaged($key);
        $photos->delete($portfolio->photo_url);
        $portfolio->delete();

        session(['portfolio_keys' => array_values(array_filter(
            session('portfolio_keys', []),
            fn (string $sessionKey) => ! hash_equals($key, $sessionKey),
        ))]);

        return redirect()->route('home')->with('status', 'Portfolio deleted.');
    }

    public function templates(string $key): mixed
    {
        return view('portfolios.templates', [
            'portfolio' => $this->findManaged($key),
            'key' => $key,
            'templates' => self::TEMPLATES,
        ]);
    }

    public function updateTemplate(Request $request, string $key): mixed
    {
        $portfolio = $this->findManaged($key);
        $data = $request->validate(['template' => ['required', 'in:'.implode(',', array_keys(self::TEMPLATES))]]);
        $portfolio->update($data);

        return redirect()->route('portfolios.manage.show', $key)->with('status', 'Template updated.');
    }

    public function preview(string $key): mixed
    {
        return $this->renderPortfolio($this->findManaged($key));
    }

    public function public(string $slug): mixed
    {
        return $this->renderPortfolio(Portfolio::where('slug', $slug)->firstOrFail());
    }

    private function renderPortfolio(Portfolio $portfolio): mixed
    {
        return view('portfolios.public.'.$portfolio->template, compact('portfolio'));
    }

    private function findManaged(string $key): Portfolio
    {
        abort_unless(strlen($key) === 64, 404);

        return Portfolio::where('management_key_hash', hash('sha256', $key))->firstOrFail();
    }

    private function validatedInformation(Request $request, bool $creating = false): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'address' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string', 'max:3000'],
            'education_text' => ['nullable', 'string', 'max:6000'],
            'skills_text' => ['nullable', 'string', 'max:3000'],
            'projects_text' => ['nullable', 'string', 'max:9000'],
            'experience_text' => ['nullable', 'string', 'max:9000'],
            'links_text' => ['nullable', 'string', 'max:3000'],
            'profile_picture' => [$creating ? 'nullable' : 'sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);
    }

    private function portfolioFields(array $data): array
    {
        return [
            ...Arr::only($data, ['name', 'email', 'phone', 'address', 'about']),
            'education' => $this->parseRows($data['education_text'] ?? '', 3),
            'skills' => $this->parseSkills($data['skills_text'] ?? ''),
            'projects' => $this->parseRows($data['projects_text'] ?? '', 3),
            'experience' => $this->parseRows($data['experience_text'] ?? '', 4),
            'links' => $this->parseRows($data['links_text'] ?? '', 2),
        ];
    }

    private function formValues(Portfolio $portfolio): array
    {
        $formatRows = fn (?array $rows) => collect($rows ?? [])
            ->map(fn (array $row) => implode(' | ', $row))
            ->implode("\n");

        return [
            'name' => $portfolio->name,
            'email' => $portfolio->email,
            'phone' => $portfolio->phone,
            'address' => $portfolio->address,
            'about' => $portfolio->about,
            'education_text' => $formatRows($portfolio->education),
            'skills_text' => implode(', ', $portfolio->skills ?? []),
            'projects_text' => $formatRows($portfolio->projects),
            'experience_text' => $formatRows($portfolio->experience),
            'links_text' => $formatRows($portfolio->links),
            'photo_url' => $portfolio->photo_url,
        ];
    }

    private function parseRows(string $text, int $columns): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $text) ?: [])
            ->map(fn (string $line) => array_map('trim', array_slice(array_pad(explode('|', $line, $columns), $columns, ''), 0, $columns)))
            ->filter(fn (array $row) => collect($row)->contains(fn (string $value) => $value !== ''))
            ->values()
            ->all();
    }

    private function parseSkills(string $text): array
    {
        return collect(preg_split('/[,\r\n]+/', $text) ?: [])
            ->map(fn (string $skill) => trim($skill))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}