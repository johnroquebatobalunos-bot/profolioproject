@extends('layouts.app')

@section('title', 'Create a portfolio · PortfolioForm')

@section('content')
<div class="page-shell form-page">
    @guest
        <a class="back-link" href="{{ route('home') }}">← Workspace</a>
        <section class="auth-gate" aria-labelledby="auth-gate-title">
            <div class="auth-gate-copy">
                <p class="eyebrow">YOUR WORKSPACE</p>
                <h1 id="auth-gate-title">Sign in to continue.</h1>
                <p>Sign in before you fill out or submit your portfolio.</p>
            </div>
            <div class="auth-gate-actions">
                <a class="button button-dark auth-gate-action" href="{{ route('login') }}">Sign in <span aria-hidden="true">↗</span></a>
                <p class="auth-gate-alternate">New here? <a href="{{ route('register') }}">Create an account</a></p>
            </div>
        </section>
    @else
        <a class="back-link" href="{{ route('home') }}">← Workspace</a>
        <div class="page-intro form-intro">
            <p class="eyebrow">NEW PORTFOLIO</p>
            <h1>Let’s make this <em>yours.</em></h1>
            <p>Start with the essentials.</p>
        </div>
        @if ($errors->any())<div class="form-alert" role="alert">A few details need another look. Check the highlighted fields below.</div>@endif
        @include('portfolios._form', [
            'action' => route('portfolios.store'),
            'method' => 'post',
            'formValues' => [],
            'cancelUrl' => route('home'),
            'submitLabel' => 'Create portfolio',
        ])
    @endguest
</div>
@endsection