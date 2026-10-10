<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Services\PayPalGateway;
use App\Services\RazorpayGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_page_disables_unconfigured_gateways(): void
    {
        config(['services.paypal.client_id' => null, 'services.paypal.client_secret' => null,
            'services.razorpay.key_id' => null, 'services.razorpay.key_secret' => null]);
        $this->get('/Payment')->assertOk()->assertSee('PayPal is temporarily unavailable')->assertSee('Razorpay is temporarily unavailable');
    }

    public function test_paypal_fee_is_whitelisted_and_tax_is_exactly_ten_percent(): void
    {
        $this->post('/PaymentDetails', ['fee' => 21])->assertSessionHasErrors('fee');
        $response = $this->post('/PaymentDetails', ['fee' => 75]);
        $response->assertRedirect();
        $this->get($response->headers->get('Location'))->assertOk()->assertSee('$75.00 USD')->assertSee('$7.50 USD')->assertSee('$82.50 USD');
    }

    public function test_razorpay_rejects_bad_signature_without_marking_paid(): void
    {
        $payment = Payment::create(['provider' => 'razorpay', 'provider_order_id' => 'order_1', 'amount_minor' => 5000,
            'total_minor' => 5000, 'currency' => 'INR', 'reference' => '930ca597-754f-4c22-8429-44dcdeae50bd', 'status' => 'pending']);
        $gateway = Mockery::mock(RazorpayGateway::class); $gateway->shouldReceive('verifySignature')->once()->andThrow(new \RuntimeException('bad signature'));
        $this->app->instance(RazorpayGateway::class, $gateway);
        $this->post('/pay/razorpay/success', ['razorpay_payment_id' => 'pay_1', 'razorpay_order_id' => 'order_1',
            'razorpay_signature' => 'bad', 'reference' => $payment->reference])->assertStatus(422);
        $this->assertSame('pending', $payment->fresh()->status);
    }

    public function test_razorpay_amount_mismatch_is_rejected(): void
    {
        $payment = Payment::create(['provider' => 'razorpay', 'provider_order_id' => 'order_2', 'amount_minor' => 5000,
            'total_minor' => 5000, 'currency' => 'INR', 'reference' => '7ba40a1d-2ea8-4edf-be4b-3710bc413a4a', 'status' => 'pending']);
        $gateway = Mockery::mock(RazorpayGateway::class); $gateway->shouldReceive('verifySignature')->once();
        $gateway->shouldReceive('fetchOrder')->andReturn(['amount' => 4000, 'currency' => 'INR']);
        $gateway->shouldReceive('fetchPayment')->andReturn(['order_id' => 'order_2', 'amount' => 4000, 'currency' => 'INR', 'status' => 'captured']);
        $this->app->instance(RazorpayGateway::class, $gateway);
        $this->post('/pay/razorpay/success', ['razorpay_payment_id' => 'pay_2', 'razorpay_order_id' => 'order_2',
            'razorpay_signature' => 'valid', 'reference' => $payment->reference])->assertStatus(422);
        $this->assertSame('pending', $payment->fresh()->status);
    }

    public function test_paypal_incomplete_capture_is_rejected(): void
    {
        $payment = Payment::create(['provider' => 'paypal', 'provider_order_id' => 'ORDER3', 'amount_minor' => 5500,
            'base_minor' => 5000, 'tax_minor' => 500, 'total_minor' => 5500, 'currency' => 'USD',
            'reference' => '5cd2daf4-228e-4651-abf8-aaef36835840', 'status' => 'pending']);
        $gateway = Mockery::mock(PayPalGateway::class); $gateway->shouldReceive('captureOrder')->once()->andReturn(['id' => 'ORDER3', 'status' => 'APPROVED']);
        $this->app->instance(PayPalGateway::class, $gateway);
        $this->postJson('/pay/paypal/capture', ['order_id' => 'ORDER3', 'reference' => $payment->reference])->assertStatus(422);
        $this->assertSame('pending', $payment->fresh()->status);
    }

    public function test_paypal_success_is_persisted_and_idempotent(): void
    {
        $payment = Payment::create(['provider' => 'paypal', 'provider_order_id' => 'ORDER4', 'amount_minor' => 3300,
            'base_minor' => 3000, 'tax_minor' => 300, 'total_minor' => 3300, 'currency' => 'USD',
            'reference' => '3639d63f-c488-4b0e-b90d-28b8920ae827', 'status' => 'pending']);
        $result = ['id' => 'ORDER4', 'status' => 'COMPLETED', 'purchase_units' => [['payments' => ['captures' => [[
            'id' => 'CAPTURE4', 'status' => 'COMPLETED', 'amount' => ['currency_code' => 'USD', 'value' => '33.00']]]]]],
            'payer' => ['email_address' => 'author@example.com', 'name' => ['given_name' => 'Ada', 'surname' => 'Lovelace']]];
        $gateway = Mockery::mock(PayPalGateway::class); $gateway->shouldReceive('captureOrder')->once()->andReturn($result);
        $this->app->instance(PayPalGateway::class, $gateway);
        $payload = ['order_id' => 'ORDER4', 'reference' => $payment->reference];
        $this->postJson('/pay/paypal/capture', $payload)->assertOk();
        $this->postJson('/pay/paypal/capture', $payload)->assertOk();
        $this->assertDatabaseHas('public_payments', ['reference' => $payment->reference, 'status' => 'paid', 'provider_payment_id' => 'CAPTURE4']);
        $this->assertSame(1, Payment::where('reference', $payment->reference)->count());
    }

    public function test_razorpay_cancellation_is_session_bound_and_persisted(): void
    {
        $payment = Payment::create(['provider' => 'razorpay', 'provider_order_id' => 'order_cancel', 'amount_minor' => 2500,
            'total_minor' => 2500, 'currency' => 'INR', 'reference' => '967f7aa4-fd50-45be-a6cb-88adb3aa15c3', 'status' => 'pending']);
        $payload = ['reference' => $payment->reference, 'status' => 'cancelled', 'reason' => 'Checkout window closed'];
        $this->postJson('/pay/razorpay/abandon', $payload)->assertForbidden();
        $this->assertSame('pending', $payment->fresh()->status);
        $this->withSession(['razorpay_payments' => [$payment->reference => now()->timestamp]])
            ->postJson('/pay/razorpay/abandon', $payload)->assertOk()->assertJsonStructure(['redirect']);
        $this->assertDatabaseHas('public_payments', ['reference' => $payment->reference, 'status' => 'cancelled']);
    }

    public function test_razorpay_failure_cannot_overwrite_paid_transaction(): void
    {
        $payment = Payment::create(['provider' => 'razorpay', 'provider_order_id' => 'order_paid', 'provider_payment_id' => 'pay_paid',
            'amount_minor' => 2500, 'total_minor' => 2500, 'currency' => 'INR',
            'reference' => '21c348ca-6b83-4f78-bb64-b413745a9a15', 'status' => 'paid', 'paid_at' => now()]);
        $this->withSession(['razorpay_payments' => [$payment->reference => now()->timestamp]])
            ->postJson('/pay/razorpay/abandon', ['reference' => $payment->reference, 'status' => 'failed'])->assertStatus(409);
        $this->assertSame('paid', $payment->fresh()->status);
    }
}
