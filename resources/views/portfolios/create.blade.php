@extends('layouts.app')

@section('title', 'Create a portfolio · PortfolioForm')

@section('content')
<div class="page-shell form-page">
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
</div>
@endsection