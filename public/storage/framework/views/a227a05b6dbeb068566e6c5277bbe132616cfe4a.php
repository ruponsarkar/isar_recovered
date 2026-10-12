<?php $__env->startSection('content'); ?>
    <link href="<?php echo e(asset('payment.css')); ?>" rel="stylesheet">
    <div class="container py-4">
        <div class="row g-3">
            <aside class="col-lg-3 d-none d-lg-block"><?php echo $__env->make('pages.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></aside>
            <section class="col-lg-9">
                <article class="payment-summary-card">
                    <div class="summary-head"><span>Secure checkout</span>
                        <h1>Payment Details</h1>
                        <p>Review the final amount before opening PayPal.</p>
                    </div>
                    <div class="summary-lines">
                        <div><span>Processing Fees</span><strong>$<?php echo e(number_format($baseMinor / 100, 2)); ?> USD</strong></div>
                        <div><span>Tax <small>(10%)</small></span><strong>$<?php echo e(number_format($taxMinor / 100, 2)); ?> USD</strong>
                        </div>
                        <div class="summary-total"><span>Total</span><strong>$<?php echo e(number_format($totalMinor / 100, 2)); ?>

                                USD</strong></div>
                    </div>
                    <div id="paypal-buttons"></div>
                    <?php if (! ($paypalConfigured)): ?>
                        <div class="gateway-unavailable"><i class="fa-solid fa-circle-info"></i> PayPal checkout is temporarily
                            unavailable. No payment has been created. Please contact the editorial office.</div>
                    <?php endif; ?>
                    <div id="paypal-message" class="gateway-message" role="status" aria-live="polite"></div><a
                        href="<?php echo e(route('payment')); ?>" class="summary-back"><i class="fa-solid fa-arrow-left"></i> Choose
                        another payment option</a>
                    <div class="summary-trust"><i class="fa-solid fa-shield-halved"></i><span><strong>Verified by
                                ISAR</strong>Your amount is calculated on the server. Card details are handled only by
                            PayPal.</span></div>
                </article>
            </section>
            <aside class="col-12 d-lg-none"><?php echo $__env->make('pages.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></aside>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php if($paypalConfigured): ?>
    <?php $__env->startPush('scripts'); ?>
        <script src="https://www.paypal.com/sdk/js?client-id=<?php echo e(urlencode($paypalClientId)); ?>&currency=USD&intent=capture">
        </script>
        <script>
            let paymentReference = null;
            const paypalMessage = document.getElementById('paypal-message');
            paypal.Buttons({
                createOrder: async function() {
                    paypalMessage.textContent = 'Creating secure order…';
                    const response = await fetch(<?php echo json_encode(route('pay.paypal.order'), 15, 512) ?>, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': <?php echo json_encode(csrf_token(), 15, 512) ?>
                        },
                        body: JSON.stringify({
                            quote: <?php echo json_encode($quoteId, 15, 512) ?>
                        })
                    });
                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message || 'Unable to create order.');
                    paymentReference = data.reference;
                    paypalMessage.textContent = '';
                    return data.id
                },
                onApprove: async function(data) {
                    paypalMessage.textContent = 'Verifying payment…';
                    const response = await fetch(<?php echo json_encode(route('pay.paypal.capture'), 15, 512) ?>, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': <?php echo json_encode(csrf_token(), 15, 512) ?>
                        },
                        body: JSON.stringify({
                            order_id: data.orderID,
                            reference: paymentReference
                        })
                    });
                    const result = await response.json();
                    if (!response.ok) throw new Error(result.message || 'Payment verification failed.');
                    window.location.assign(result.redirect)
                },
                onCancel: function() {
                    paypalMessage.textContent = 'Checkout was cancelled. No payment was recorded.'
                },
                onError: function(error) {
                    paypalMessage.textContent = error.message || 'PayPal checkout could not be completed.'
                }
            }).render('#paypal-buttons');
        </script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/ruponsarkar/Documents/projects/isar/isar/public/resources/views/pages/payment-details.blade.php ENDPATH**/ ?>