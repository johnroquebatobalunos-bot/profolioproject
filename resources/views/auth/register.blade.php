@extends('layouts.app')

@section('title', 'Create an account · PortfolioForm')

@section('content')
<div class="page-shell login-page">
    <section class="login-panel" aria-labelledby="register-title">
        <div class="page-intro">
            <p class="eyebrow">YOUR WORKSPACE</p>
            <h1 id="register-title">Create your account.</h1>
            <p>Sign up to create and manage your portfolio.</p>
        </div>

        @if ($errors->any())
            <div class="form-alert" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form class="login-form" method="POST" action="{{ route('register.store') }}">
            @csrf
            <label class="field" for="name">
                Name <span class="required">*</span>
                <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" maxlength="255" required autofocus>
                @error('name')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label class="field" for="email">
                Email address <span class="required">*</span>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
                @error('email')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label class="field" for="password">
                Password <span class="required">*</span>
                <input id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
                @error('password')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label class="field" for="password_confirmation">
                Confirm password <span class="required">*</span>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required>
            </label>
            <button class="button button-dark" type="submit">Create account <span aria-hidden="true">↗</span></button>
        </form>
        <p class="login-switch">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
    </section>
</div>
@endsection