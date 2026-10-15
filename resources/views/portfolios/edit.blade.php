@extends('layouts.app')

@section('title', 'Edit '.$portfolio->name.' · Folioform')

@section('content')
<div class="page-shell form-page">
    <a class="back-link" href="{{ route('portfolios.manage.show', $key) }}">← Portfolio overview</a>
    <div class="page-intro form-intro">
        <p class="eyebrow">EDIT PORTFOLIO</p>
        <h1>Keep it <em>current.</em></h1>
        <p>Update the details that tell your story.</p>
    </div>
    @if ($errors->any())<div class="form-alert" role="alert">A few details need another look. Check the highlighted fields below.</div>@endif
    @include('portfolios._form', [
        'action' => route('portfolios.manage.update', $key),
        'method' => 'put',
        'formValues' => $formValues,
        'cancelUrl' => route('portfolios.manage.show', $key),
        'submitLabel' => 'Save changes',
    ])
</div>
@endsection