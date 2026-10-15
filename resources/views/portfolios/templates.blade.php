@extends('layouts.app')

@section('title', 'Choose a template · Folioform')

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
                    @if ($slug === 'simple')
                        <div class="mini-simple">
                            <div class="mini-preview-top"><span>JORDAN LEE</span><span>01 / 03</span></div>
                            <div class="mini-simple-main"><div><small>DESIGNER / BROOKLYN</small><b>Thoughtful<br>by design.</b><span>Selected work <i>↗</i></span></div><img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&amp;fit=crop&amp;w=260&amp;q=80" alt=""></div>
                            <div class="mini-simple-work"><span>01 BRAND</span><span>02 DIGITAL</span><span>03 EDITORIAL</span></div>
                        </div>
                    @elseif ($slug === 'modern')
                        <div class="mini-modern">
                            <div class="mini-preview-top"><span>F / JORDAN LEE</span><span>PORTFOLIO — {{ date('Y') }}</span></div>
                            <div class="mini-modern-main"><img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&amp;fit=crop&amp;w=280&amp;q=80" alt=""><div><small>INDEPENDENT CREATIVE</small><b>Make good<br>things happen.</b></div></div>
                            <div class="mini-modern-work"><span>SELECTED WORK</span><i></i><i></i><i></i></div>
                        </div>
                    @else
                        <div class="mini-creative">
                            <div class="mini-preview-top"><span>MAKE / MORE</span><span>© {{ date('Y') }}</span></div>
                            <div class="mini-creative-main"><b>MAKE<br>WAVES</b><img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&amp;fit=crop&amp;w=250&amp;q=80" alt=""><i></i></div>
                            <div class="mini-creative-work"><span>IDEAS IN MOTION</span><span>↗ PROJECTS</span></div>
                        </div>
                    @endif
                </div>
                <div class="template-choice-info"><div><span class="template-number">0{{ $loop->iteration }}</span><h2>{{ $name }}</h2></div><button class="template-select-button" type="submit">{{ $portfolio->template === $slug ? 'Selected' : 'Use template' }} <span aria-hidden="true">↗</span></button></div>
                <p>{{ ['A quiet, editorial layout. Clear, classic, and all about the work.', 'A crisp, modular canvas with bold sections and project cards.', 'An expressive composition for a portfolio with its own point of view.'][$loop->index] }}</p>
            </form>
        @endforeach
    </div>
</div>
@endsection