@extends('layouts.app')

@section('title', 'Your workspace · Folioform')

@section('content')
<div class="page-shell">
    <section class="dashboard-hero">
        <div class="hero-copy">
            <p class="eyebrow"><span class="status-dot"></span> YOUR WORK, ON THE WEB</p>
            <h1>A portfolio that <em>opens doors.</em></h1>
            <p class="hero-description">Bring your experience, ideas, and best work together in one thoughtful place.</p>
            <a class="button button-dark hero-action" href="{{ route('portfolios.create') }}">Start your portfolio <span aria-hidden="true">↗</span></a>
            <div class="template-count"><span>01</span> Simple <i></i><span>02</span> Modern <i></i><span>03</span> Creative</div>
        </div>
        <div class="hero-art" aria-label="Portfolio preview">
            <div class="art-topline"><span>PORTFOLIO / {{ date('Y') }}</span><span>01 — 03</span></div>
            <div class="art-photo-wrap">
                <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&amp;fit=crop&amp;w=1000&amp;q=85" alt="Portrait of a man in formal attire">
                <span class="art-stamp">A little<br>more you.</span>
            </div>
            <div class="art-caption"><strong>Good work deserves<br>a good home.</strong><span>FIG. 01</span></div>
            <div class="art-tab">YOUR NEXT CHAPTER <span>↗</span></div>
        </div>
    </section>

    <section class="workspace-section" aria-labelledby="workspace-title">
        <div class="section-heading">
            <div><p class="eyebrow">YOUR WORKSPACE</p><h2 id="workspace-title">Portfolios <span class="count-pill">{{ $portfolios->count() }}</span></h2></div>
            <a class="text-link" href="{{ route('portfolios.create') }}">Create another <span aria-hidden="true">↗</span></a>
        </div>

        @if ($portfolios->isNotEmpty())
            <div class="portfolio-list">
                @foreach ($portfolios as $portfolio)
                    <article class="portfolio-row">
                        <div class="row-avatar" aria-hidden="true">
                            @if ($portfolio->photo_url)<img src="{{ $portfolio->photo_url }}" alt="">@else<span>{{ strtoupper(substr($portfolio->name, 0, 1)) }}</span>@endif
                        </div>
                        <div class="row-main">
                            <h3>{{ $portfolio->name }}</h3>
                            <p>{{ $portfolio->email }} <span>·</span> Updated {{ $portfolio->updated_at->diffForHumans() }}</p>
                        </div>
                        <span class="template-tag tag-{{ $portfolio->template }}">{{ ucfirst($portfolio->template) }}</span>
                        @php($portfolioKey = collect(session('portfolio_keys', []))->first(fn ($key) => hash_equals($portfolio->management_key_hash, hash('sha256', $key))))
                        <a class="row-open" href="{{ route('portfolios.manage.show', $portfolioKey) }}" aria-label="Open {{ $portfolio->name }} management">↗</a>
                    </article>
                @endforeach
            </div>
        @else
            <div class="empty-workspace">
                <div class="empty-mark">+</div>
                <div><h3>Your next chapter starts here.</h3><p>Create a portfolio to gather your work and give it a place to live.</p></div>
                <a class="button button-outline" href="{{ route('portfolios.create') }}">Create a portfolio <span aria-hidden="true">↗</span></a>
            </div>
        @endif
    </section>

    <section class="bottom-note">
        <span class="note-index">A / 03</span>
        <p>One set of details.<br><em>Three ways to show up.</em></p>
        <div class="note-shapes" aria-hidden="true"><i></i><i></i><i></i></div>
    </section>
</div>
@endsection