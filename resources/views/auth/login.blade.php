@extends('layouts.app')

@section('title', 'Sign in · PortfolioForm')

@section('content')
<div class="page-shell login-page">
    <section class="login-panel" aria-labelledby="login-title">
        <div class="page-intro">
            <p class="eyebrow">YOUR WORKSPACE</p>
            <h1 id="login-title">Sign in to continue.</h1>
            <p>Use your account to return to the portfolio form.</p>
        </div>

        @if ($errors->any())
            <div class="form-alert" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form class="login-form" method="POST" action="{{ route('login.store') }}">
            @csrf
            <label class="field" for="email">
                Email address <span class="required">*</span>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                @error('email')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label class="field" for="password">
                Password <span class="required">*</span>
                <input id="password" name="password" type="password" autocomplete="current-password" required>
                @error('password')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label class="auth-remember" for="remember">
                <input id="remember" name="remember" type="checkbox" value="1">
                <span>Remember me</span>
            </label>
            <button class="button button-dark" type="submit">Sign in <span aria-hidden="true">↗</span></button>
        </form>
        <p class="login-switch">Don’t have an account? <a href="{{ route('register') }}">Create one</a></p>
    </section>
</div>
@endsection