@extends('layouts.app')

@section('title', $portfolio->name.' · Manage portfolio')

@section('content')
<div class="page-shell manage-page">
    <a class="back-link" href="{{ route('home') }}">← Workspace</a>
    <div class="manage-heading">
        <div>
            <p class="eyebrow">PORTFOLIO OVERVIEW <span class="intro-sep">/</span> {{ strtoupper($portfolio->template) }}</p>
            <h1>{{ $portfolio->name }}</h1>
            <p class="manage-subtitle">Your portfolio is ready to share.</p>
        </div>
        <a class="button button-dark" href="{{ route('portfolios.manage.preview', request()->route('key')) }}" target="_blank" rel="noopener">Preview live site <span aria-hidden="true">↗</span></a>
    </div>

    <div class="manage-grid">
        <section class="manage-main-panel">
            <div class="panel-topline"><span>PUBLIC LINK</span><span class="live-state"><i></i> LIVE</span></div>
            <a class="public-url" href="{{ $publicUrl }}" target="_blank" rel="noopener">{{ $publicUrl }} <span aria-hidden="true">↗</span></a>
            <div class="manage-actions">
                <a class="button button-outline" href="{{ route('portfolios.manage.edit', request()->route('key')) }}">Edit information</a>
                <a class="button button-outline" href="{{ route('portfolios.manage.templates', request()->route('key')) }}">Change template</a>
                <button class="button button-soft" type="button" data-copy="{{ $publicUrl }}">Copy public link</button>
            </div>
            <span class="copy-status" role="status" aria-live="polite"></span>
        </section>

        <aside class="manage-side-panel">
            <p class="eyebrow">CURRENT LOOK</p>
            <div class="template-preview-chip chip-{{ $portfolio->template }}"><span>{{ str_pad((string) array_search($portfolio->template, ['simple', 'modern', 'creative'], true) + 1, 2, '0', STR_PAD_LEFT) }}</span><strong>{{ $templateName }}</strong><i>↗</i></div>
            <p class="side-copy">{{ $portfolio->projects ? count($portfolio->projects).' projects' : 'No projects yet' }} <span>·</span> {{ $portfolio->skills ? count($portfolio->skills).' skills' : 'No skills added' }}</p>
        </aside>
    </div>

    <section class="danger-zone">
        <div><p class="eyebrow">NEED A RESET?</p><h2>Remove this portfolio</h2><p>This permanently deletes your portfolio and its public page.</p></div>
        <form action="{{ route('portfolios.manage.destroy', request()->route('key')) }}" method="post" onsubmit="return confirm('Delete this portfolio permanently?')">
            @csrf @method('DELETE')
            <button class="button button-danger" type="submit">Delete portfolio</button>
        </form>
    </section>
</div>
@endsection