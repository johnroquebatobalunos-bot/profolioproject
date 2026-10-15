@extends('layouts.portfolio')

@section('portfolio-content')
<div class="public-simple">
    <header class="simple-topbar"><a href="{{ route('portfolios.public', $portfolio->slug) }}" class="portfolio-wordmark">FOLIOFORM <span> / PERSONAL WORK</span></a><span>AVAILABLE FOR OPPORTUNITIES</span></header>
    <main class="simple-wrap">
        <section class="simple-hero">
            <div class="simple-intro">
                <p class="portfolio-kicker">HELLO, I'M</p>
                <h1>{{ $portfolio->name }}</h1>
                <p class="simple-role">{{ $portfolio->address ?: 'Independent creative' }}</p>
                @if ($portfolio->about)<p class="simple-about">{{ $portfolio->about }}</p>@endif
                <a class="simple-contact" href="mailto:{{ $portfolio->email }}">LET'S TALK <span>↗</span></a>
            </div>
            @if ($portfolio->photo_url)<img class="simple-photo" src="{{ $portfolio->photo_url }}" alt="Portrait of {{ $portfolio->name }}">@endif
        </section>

        @if ($portfolio->projects)
        <section class="simple-section"><div class="simple-section-heading"><span>01</span><h2>Selected work</h2></div>
            @foreach ($portfolio->projects as $project)
                <article class="simple-project"><span class="simple-project-count">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><div><h3>{{ $project[0] ?? '' }}</h3><p>{{ $project[1] ?? '' }}</p></div>@if (!empty($project[2]) && preg_match('/^https?:\/\//i', $project[2]))<a href="{{ $project[2] }}" target="_blank" rel="noopener noreferrer" aria-label="Open {{ $project[0] ?? 'project' }}">↗</a>@endif</article>
            @endforeach
        </section>
        @endif

        <div class="simple-two-col">
            @if ($portfolio->experience)
            <section class="simple-section"><div class="simple-section-heading"><span>02</span><h2>Experience</h2></div>
                @foreach ($portfolio->experience as $job)<article class="simple-timeline"><h3>{{ $job[0] ?? '' }}</h3><p>{{ $job[1] ?? '' }} <span>·</span> {{ $job[2] ?? '' }}</p><p class="timeline-description">{{ $job[3] ?? '' }}</p></article>@endforeach
            </section>
            @endif
            @if ($portfolio->education)
            <section class="simple-section"><div class="simple-section-heading"><span>03</span><h2>Education</h2></div>
                @foreach ($portfolio->education as $school)<article class="simple-timeline"><h3>{{ $school[0] ?? '' }}</h3><p>{{ $school[1] ?? '' }} <span>·</span> {{ $school[2] ?? '' }}</p></article>@endforeach
            </section>
            @endif
        </div>

        @if ($portfolio->skills)
        <section class="simple-section"><div class="simple-section-heading"><span>04</span><h2>Things I do</h2></div><div class="simple-skills">@foreach ($portfolio->skills as $skill)<span>{{ $skill }}</span>@endforeach</div></section>
        @endif
        <footer class="simple-footer"><a href="mailto:{{ $portfolio->email }}">{{ $portfolio->email }}</a><div>@foreach ($portfolio->links ?? [] as $link)@if (!empty($link[0]) && !empty($link[1]) && preg_match('/^https?:\/\//i', $link[1]))<a href="{{ $link[1] }}" target="_blank" rel="noopener noreferrer">{{ $link[0] }} ↗</a>@endif @endforeach</div><span>© {{ date('Y') }} {{ $portfolio->name }}</span></footer>
    </main>
</div>
@endsection