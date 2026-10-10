@extends('layouts.app')
@section('content')
<link href="{{ asset('payment.css') }}" rel="stylesheet">
<div class="container py-5"><div class="row g-3">
<aside class="col-lg-3 d-none d-lg-block">@include('pages.sidebar')</aside>
<section class="col-lg-9"><article class="payment-result {{ $successful ? 'is-success' : 'is-failure' }}"><div class="result-icon"><i class="fa-solid {{ $successful ? 'fa-check' : 'fa-xmark' }}"></i></div><h1>{{ $successful ? 'Payment successful' : 'Payment not confirmed' }}</h1>@if($successful)<p>Thank you. Your secure payment has been verified and recorded.</p><dl><div><dt>Transaction reference</dt><dd>{{ $payment->reference }}</dd></div><div><dt>Amount</dt><dd>{{ $payment->currency }} {{ number_format($payment->total_minor/100,2) }}</dd></div><div><dt>Provider</dt><dd>{{ ucfirst($payment->provider) }}</dd></div></dl>@else<p>We could not verify this transaction. No successful payment has been recorded. If money was deducted, contact the editorial office with the reference below.</p><code>{{ $reference ?? 'Reference unavailable' }}</code>@endif<div class="mt-4"><a class="btn btn-isar" href="{{ route('payment') }}">Return to payments</a> <a class="btn btn-outline-secondary" href="mailto:contact@isarpublisher.com">Contact support</a></div></article></section>
<aside class="col-12 d-lg-none">@include('pages.sidebar')</aside>
</div></div>
@endsection
