@extends('layouts.app')
@section('content')
<div class="container py-4">
    <article class="paper-card">
        <div class="policy-hero"><h1>Contact Us</h1></div>
        <div class="paper-body policy-copy">
            <p><b>{{ data_get($journal ?? null, 'j_name') ?: 'International Scientific and Academic Research Publisher' }}</b></p>
            @include('partials.contact-details')
        </div>
    </article>
</div>
@endsection
