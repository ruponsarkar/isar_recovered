@extends('layouts.app')
@section('content')
    <link href="{{ asset('payment.css') }}" rel="stylesheet">
    <div class="container mt-2 payment-page">
        <div class="row g-3">
            <aside class="col-lg-3 d-none d-lg-block">@include('pages.sidebar')</aside>
            <section class="col-lg-9">
                <article class="paper-card payment-heading">
                    <div class="section-title">Payments</div>
                    <div class="payment-intro"><span class="trust-mark"><i class="fa-solid fa-shield-halved"></i></span>
                        <div><strong>Secure publication fee payment</strong>
                            <p>Choose the route for your location. Your transaction is created and verified securely on our
                                server; ISAR never stores card details.</p>
                        </div>
                    </div>
                </article>
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
                @endif
                <div class="payment-split">
                    <article class="payment-panel foreign-panel">
                        <header><span class="author-label">International payments</span>
                            <h2>Foreign Authors</h2>
                            <p>Foreign authors can conveniently pay their processing fees online in USD using PayPal, Credit
                                Card, or Debit Card.</p>
                        </header>
                        <fieldset class="method-picker">
                            <legend>Choose Payment method:</legend><label><input type="radio" name="foreign_method"
                                    value="paypal" checked><span><i class="fa-brands fa-paypal"></i>
                                    PayPal</span></label><label><input type="radio" name="foreign_method"
                                    value="razorpay"><span><i class="fa-solid fa-credit-card"></i> Razorpay</span></label>
                        </fieldset>
                        <div id="paypal-option" class="gateway-box">
                            <form method="post" action="{{ route('payment.details') }}">@csrf<label class="form-label"
                                    for="fee">Select processing fee</label><select class="form-select" id="fee"
                                    name="fee" required>
                                    <option value="">— Select fee —</option>
                                    @foreach ($paypalFees as $fee)
                                        <option value="{{ $fee }}">${{ $fee }} USD</option>
                                    @endforeach
                                </select>
                                <button class="btn paypal-button w-100 mt-3" type="submit" @disabled(!$paypalConfigured)><i
                                        class="fa-brands fa-paypal"></i> Pay Now With PayPal</button>
                            </form>
                            @unless ($paypalConfigured)
                                <p class="gateway-unavailable"><i class="fa-solid fa-circle-info"></i> PayPal is temporarily
                                    unavailable. Please contact the editorial office.</p>
                            @endunless
                        </div>
                        <div id="foreign-razorpay-option" class="gateway-box d-none">@include('pages.partials.razorpay-form', [
                            'formId' => 'foreign-razorpay',
                            'currency' => 'USD',
                        ])</div>
                        @include('pages.partials.payment-instructions', ['paypal' => true])
                    </article>
                    <article class="payment-panel indian-panel">
                        <header><span class="author-label">Domestic payments</span>
                            <h2>Indian Authors</h2>
                            <p>Pay online in INR with UPI, GPay, net banking, Credit Card, or Debit Card.</p>
                        </header>
                        <div class="gateway-box indian-form">@include('pages.partials.razorpay-form', [
                            'formId' => 'indian-razorpay',
                            'currency' => 'INR',
                        ])</div>@include('pages.partials.payment-instructions', ['paypal' => false])
                    </article>
                </div>
            </section>
            <aside class="col-12 d-lg-none">@include('pages.sidebar')</aside>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.querySelectorAll('input[name="foreign_method"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.getElementById('paypal-option').classList.toggle('d-none', this.value !==
                'paypal');
                document.getElementById('foreign-razorpay-option').classList.toggle('d-none', this.value !==
                    'razorpay')
            })
        });
        async function abandonRazorpay(order, status, reason) {
            const response = await fetch(@json(route('pay.razorpay.abandon')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': @json(csrf_token())
                },
                body: JSON.stringify({
                    reference: order.reference,
                    status: status,
                    reason: reason
                })
            });
            const data = await response.json();
            if (response.ok && data.redirect) window.location.assign(data.redirect);
            return data
        }
        document.querySelectorAll('.razorpay-form').forEach(function(form) {
            form.addEventListener('submit', async function(event) {
                event.preventDefault();
                const button = form.querySelector('button');
                const message = form.querySelector('.gateway-message');
                button.disabled = true;
                const original = button.textContent;
                button.textContent = 'Preparing secure checkout…';
                message.textContent = '';
                let opened = false;
                try {
                    const response = await fetch(@json(route('pay.razorpay.order')), {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': @json(csrf_token())
                        },
                        body: new FormData(form)
                    });
                    const order = await response.json();
                    if (!response.ok) throw new Error(order.message || Object.values(order.errors ||
                        {})[0] || 'Unable to start checkout.');
                    const checkout = new Razorpay({
                        key: order.key,
                        amount: order.amount,
                        currency: order.currency,
                        name: 'ISAR Publisher',
                        description: 'Publication processing fee',
                        order_id: order.order_id,
                        prefill: order.prefill,
                        theme: {
                            color: '#408e91'
                        },
                        handler: function(result) {
                            const complete = document.createElement('form');
                            complete.method = 'POST';
                            complete.action = @json(route('pay.razorpay.success'));
                            const values = Object.assign({
                                _token: @json(csrf_token()),
                                reference: order.reference
                            }, result);
                            Object.entries(values).forEach(function(entry) {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = entry[0];
                                input.value = entry[1];
                                complete.appendChild(input)
                            });
                            document.body.appendChild(complete);
                            complete.submit()
                        },
                        modal: {
                            ondismiss: async function() {
                                message.textContent = 'Recording cancelled checkout…';
                                await abandonRazorpay(order, 'cancelled',
                                    'Checkout window closed')
                            }
                        }
                    });
                    checkout.on('payment.failed', async function(result) {
                        const reason = result.error && result.error.description ? result
                            .error.description : 'Gateway reported payment failure';
                        message.textContent = 'Recording payment failure…';
                        await abandonRazorpay(order, 'failed', reason)
                    });
                    opened = true;
                    checkout.open()
                } catch (error) {
                    message.textContent = error.message
                } finally {
                    if (!opened) {
                        button.disabled = {{ $razorpayConfigured ? 'false' : 'true' }};
                        button.textContent = original
                    }
                }
            })
        });
    </script>
@endpush
