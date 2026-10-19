@extends('layouts.app')

@section('title', 'Your workspace · PortfolioForm')

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
    </section>

    <section class="template-showcase" aria-labelledby="template-showcase-title">
        <div class="section-heading">
            <div><p class="eyebrow">THREE WAYS TO SHOW UP</p><h2 id="template-showcase-title">Explore the templates</h2></div>
            <a class="text-link" href="{{ route('portfolios.create') }}">Start your portfolio <span aria-hidden="true">↗</span></a>
        </div>
        <div class="template-grid">
            @foreach (['simple' => 'Simple', 'modern' => 'Modern', 'creative' => 'Creative'] as $slug => $name)
                <article class="template-choice showcase-template">
                    <div class="template-card-art art-{{ $slug }}" aria-hidden="true">
                        @include('portfolios._template-preview', ['slug' => $slug])
                    </div>
                    <div class="template-choice-info">
                        <div><span class="template-number">0{{ $loop->iteration }}</span><h2>{{ $name }}</h2></div>
                        <span class="showcase-label">PREVIEW</span>
                    </div>
                    <p>{{ ['A quiet, editorial layout. Clear, classic, and all about the work.', 'A crisp, modular canvas with bold sections and project cards.', 'An expressive composition for a portfolio with its own point of view.'][$loop->index] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="portfolio-process" aria-labelledby="process-title">
        <div class="section-heading">
            <div><p class="eyebrow">FROM FIRST DETAILS TO LIVE PORTFOLIO</p><h2 id="process-title">How it works</h2></div>
        </div>
        <ol class="process-steps">
            <li class="process-step">
                <span class="process-step-index">01</span>
                <h3>Create an account or sign in</h3>
                <p>Start with your account so you can return to your portfolio whenever you need to.</p>
            </li>
            <li class="process-step">
                <span class="process-step-index">02</span>
                <h3>Add your details</h3>
                <p>Fill in your profile, education, skills, projects, experience, and links in one form.</p>
            </li>
            <li class="process-step">
                <span class="process-step-index">03</span>
                <h3>Choose a look and share</h3>
                <p>Pick Simple, Modern, or Creative, preview your portfolio, then share its public link.</p>
            </li>
        </ol>
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