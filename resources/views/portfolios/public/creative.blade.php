@extends('layouts.portfolio')

@section('portfolio-content')
<div class="public-creative">
    <header class="creative-topbar"><a href="{{ route('portfolios.public', $portfolio->slug) }}">FOLIOFORM <span> / {{ date('Y') }}</span></a><a href="mailto:{{ $portfolio->email }}" class="creative-mail">SAY HELLO <b>↗</b></a></header>
    <main>
        <section class="creative-hero">
            <div class="creative-hero-label"><span>INDEPENDENT<br>BY NATURE</span><span>SCROLL<br>TO WANDER ↓</span></div>
            <div class="creative-title"><p>{{ $portfolio->address ?: 'A CREATIVE PRACTICE' }}</p><h1>{{ $portfolio->name }}<span>®</span></h1><div class="creative-subtitle"><span>IDEAS IN<br>MOTION</span><p>{{ $portfolio->about ?: 'Making useful things feel a little more human.' }}</p></div></div>
            <div class="creative-image-block">@if ($portfolio->photo_url)<img src="{{ $portfolio->photo_url }}" alt="Portrait of {{ $portfolio->name }}">@else<span>{{ strtoupper(substr($portfolio->name, 0, 1)) }}</span>@endif<div class="creative-sticker">GOOD<br>ENERGY<br>ONLY</div></div>
            <div class="creative-side-note">PORTFOLIO / {{ date('Y') }} / {{ strtoupper($portfolio->name) }}</div>
        </section>

        @if ($portfolio->projects)
        <section class="creative-projects" id="work"><div class="creative-section-title"><span>01 / THE GOOD STUFF</span><h2>Selected<br><em>projects.</em></h2><p>A handful of things made with care, curiosity, and good people.</p></div>
            <div class="creative-project-list">
                @foreach ($portfolio->projects as $project)
                    <article class="creative-project creative-project-{{ $loop->iteration % 3 }}"><span class="creative-project-number">0{{ $loop->iteration }}</span><div class="creative-project-art"><span>{{ strtoupper(substr($project[0] ?? 'P', 0, 1)) }}</span><i></i></div><div class="creative-project-info"><h3>{{ $project[0] ?? '' }}</h3><p>{{ $project[1] ?? '' }}</p>@if (!empty($project[2]) && preg_match('/^https?:\/\//i', $project[2]))<a href="{{ $project[2] }}" target="_blank" rel="noopener noreferrer">TAKE A LOOK ↗</a>@endif</div></article>
                @endforeach
            </div>
        </section>
        @endif

        <section class="creative-bottom-grid">
            @if ($portfolio->experience)<div class="creative-info-block"><span>02 / THE JOURNEY</span><h2>Where I've<br>been.</h2>@foreach ($portfolio->experience as $job)<article><h3>{{ $job[0] ?? '' }}</h3><p>{{ $job[1] ?? '' }} <span>·</span> {{ $job[2] ?? '' }}</p><small>{{ $job[3] ?? '' }}</small></article>@endforeach</div>@endif
            <div class="creative-info-block creative-info-blue"><span>03 / THE TOOLKIT</span><h2>Things I<br>bring.</h2><div class="creative-skill-list">@foreach ($portfolio->skills ?? [] as $skill)<span>{{ $skill }}</span>@endforeach</div>
                @if ($portfolio->education)<div class="creative-education"><span>ALWAYS LEARNING</span>@foreach ($portfolio->education as $school)<p><strong>{{ $school[0] ?? '' }}</strong><br>{{ $school[1] ?? '' }} · {{ $school[2] ?? '' }}</p>@endforeach</div>@endif
            </div>
        </section>
    </main>
    <footer class="creative-footer"><p>HAVE A GOOD<br>IDEA? <em>LET'S TALK.</em></p><a href="mailto:{{ $portfolio->email }}">{{ $portfolio->email }} ↗</a><div>@foreach ($portfolio->links ?? [] as $link)@if (!empty($link[0]) && !empty($link[1]) && preg_match('/^https?:\/\//i', $link[1]))<a href="{{ $link[1] }}" target="_blank" rel="noopener noreferrer">{{ $link[0] }} ↗</a>@endif @endforeach</div><small>© {{ date('Y') }} {{ $portfolio->name }}</small></footer>
</div>
@endsection