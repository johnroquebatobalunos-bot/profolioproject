@extends('layouts.portfolio')

@section('portfolio-content')
<div class="public-modern">
    <header class="modern-topbar"><a href="{{ route('portfolios.public', $portfolio->slug) }}" class="modern-mark"><span>F</span> / {{ $portfolio->name }}</a><span class="modern-meta">PORTFOLIO — {{ date('Y') }}</span><a href="mailto:{{ $portfolio->email }}" class="modern-contact">LET'S CONNECT <b>↗</b></a></header>
    <main class="modern-wrap">
        <section class="modern-hero">
            <div class="modern-hero-copy"><p class="portfolio-kicker"><i></i> {{ $portfolio->address ?: 'INDEPENDENT CREATIVE' }}</p><h1>Make good<br><em>things happen.</em></h1>@if ($portfolio->about)<p>{{ $portfolio->about }}</p>@endif<a class="modern-down" href="#work">SCROLL TO EXPLORE <span>↓</span></a></div>
            @if ($portfolio->photo_url)<div class="modern-portrait"><img src="{{ $portfolio->photo_url }}" alt="Portrait of {{ $portfolio->name }}"><span>THE PERSON<br>BEHIND THE WORK</span></div>@else<div class="modern-portrait modern-portrait-empty"><span>{{ strtoupper(substr($portfolio->name, 0, 1)) }}</span></div>@endif
            <span class="modern-hero-index">01<br>—<br>04</span>
        </section>

        @if ($portfolio->projects)
        <section class="modern-work" id="work"><div class="modern-section-heading"><div><p class="portfolio-kicker">A FEW GOOD ONES</p><h2>Selected work<span> ({{ str_pad((string) count($portfolio->projects), 2, '0', STR_PAD_LEFT) }})</span></h2></div><span>2021 — {{ date('Y') }}</span></div>
            <div class="modern-project-grid">
                @foreach ($portfolio->projects as $project)
                    <article class="modern-project-card project-card-{{ $loop->iteration % 3 }}"><div class="project-visual"><span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><i></i><b>{{ strtoupper(substr($project[0] ?? 'WORK', 0, 1)) }}</b></div><div class="project-card-copy"><div><h3>{{ $project[0] ?? '' }}</h3><p>{{ $project[1] ?? '' }}</p></div>@if (!empty($project[2]) && preg_match('/^https?:\/\//i', $project[2]))<a href="{{ $project[2] }}" target="_blank" rel="noopener noreferrer" aria-label="Open {{ $project[0] ?? 'project' }}">↗</a>@endif</div></article>
                @endforeach
            </div>
        </section>
        @endif

        <section class="modern-details">
            @if ($portfolio->experience)<div><p class="portfolio-kicker">WHERE I'VE BEEN</p><h2>Experience</h2>@foreach ($portfolio->experience as $job)<article class="modern-detail-row"><h3>{{ $job[0] ?? '' }}</h3><p>{{ $job[1] ?? '' }}</p><span>{{ $job[2] ?? '' }}</span></article>@endforeach</div>@endif
            <div class="modern-skills"><p class="portfolio-kicker">IN MY TOOLKIT</p><h2>Skills</h2><div>@foreach ($portfolio->skills ?? [] as $skill)<span>{{ $skill }}</span>@endforeach</div>
                @if ($portfolio->education)<div class="modern-education"><p class="portfolio-kicker">ALWAYS LEARNING</p>@foreach ($portfolio->education as $school)<p><strong>{{ $school[0] ?? '' }}</strong><br>{{ $school[1] ?? '' }} <span>{{ $school[2] ?? '' }}</span></p>@endforeach</div>@endif
            </div>
        </section>
    </main>
    <footer class="modern-footer"><span>HAVE A GOOD ONE IN MIND?</span><a href="mailto:{{ $portfolio->email }}">Let's talk <b>↗</b></a><div>@foreach ($portfolio->links ?? [] as $link)@if (!empty($link[0]) && !empty($link[1]) && preg_match('/^https?:\/\//i', $link[1]))<a href="{{ $link[1] }}" target="_blank" rel="noopener noreferrer">{{ $link[0] }} ↗</a>@endif @endforeach</div><small>© {{ date('Y') }} {{ $portfolio->name }}</small></footer>
</div>
@endsection