@extends('layouts.app')

@section('title', 'Choose a template · PortfolioForm')

@section('content')
<div class="page-shell template-page">
    <a class="back-link" href="{{ route('portfolios.manage.show', $key) }}">← Portfolio overview</a>
    <div class="page-intro">
        <p class="eyebrow">YOUR PORTFOLIO <span class="intro-sep">/</span> FINAL STEP</p>
        <h1>Choose your <em>point of view.</em></h1>
        <p>Pick a layout. Your details stay the same.</p>
    </div>
    <div class="template-grid">
        @foreach ($templates as $slug => $name)
            <form class="template-choice {{ $portfolio->template === $slug ? 'is-selected' : '' }}" action="{{ route('portfolios.manage.template', $key) }}" method="post">
                @csrf @method('PUT')
                <input type="hidden" name="template" value="{{ $slug }}">
                <div class="template-card-art art-{{ $slug }}" aria-hidden="true">
                    @include('portfolios._template-preview', ['slug' => $slug])
                </div>
                <div class="template-choice-info"><div><span class="template-number">0{{ $loop->iteration }}</span><h2>{{ $name }}</h2></div><button class="template-select-button" type="submit">{{ $portfolio->template === $slug ? 'Selected' : 'Use template' }} <span aria-hidden="true">↗</span></button></div>
                <p>{{ ['A quiet, editorial layout. Clear, classic, and all about the work.', 'A crisp, modular canvas with bold sections and project cards.', 'An expressive composition for a portfolio with its own point of view.'][$loop->index] }}</p>
            </form>
        @endforeach
    </div>
</div>
@endsection