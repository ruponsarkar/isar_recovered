<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PayPalGateway;
use App\Services\RazorpayGateway;
use App\Services\PublicSidebarData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;

class PaymentController extends Controller
{
    public const PAYPAL_FEES = [20, 30, 50, 75, 100, 150, 200];

    public function __construct(private RazorpayGateway $razorpay, private PayPalGateway $paypal, private PublicSidebarData $sidebar) {}

    public function index()
    {
        return view('pages.payment', $this->sidebar->get() + ['paypalFees' => self::PAYPAL_FEES,
            'paypalConfigured' => $this->paypal->configured(), 'razorpayConfigured' => $this->razorpay->configured(),
        ]);
    }

    public function details(Request $request)
    {
        $data = $request->validate(['fee' => ['required', 'integer', Rule::in(self::PAYPAL_FEES)]]);
        $quote = Str::uuid()->toString();
        $request->session()->put('payment_quotes.'.$quote, ['fee' => (int) $data['fee'], 'created_at' => now()->timestamp]);
        return redirect()->route('payment.details.show', ['quote' => $quote]);
    }

    public function showDetails(Request $request)
    {
        $quoteId = (string) $request->query('quote');
        $quote = $request->session()->get('payment_quotes.'.$quoteId);
        if (!$quote || !in_array((int) ($quote['fee'] ?? 0), self::PAYPAL_FEES, true) || now()->timestamp - ($quote['created_at'] ?? 0) > 3600) {
            return redirect()->route('payment')->withErrors(['fee' => 'Please select a valid fee to continue.']);
        }
        $baseMinor = (int) $quote['fee'] * 100; $taxMinor = (int) round($baseMinor * .10);
        return view('pages.payment-details', $this->sidebar->get() + ['quoteId' => $quoteId, 'baseMinor' => $baseMinor,
            'taxMinor' => $taxMinor, 'totalMinor' => $baseMinor + $taxMinor,
            'paypalConfigured' => $this->paypal->configured(), 'paypalClientId' => config('services.paypal.client_id')]);
    }

    public function razorpayOrder(Request $request): JsonResponse
    {
        $data = $request->validate(['name' => 'required|string|max:150', 'email' => 'required|email|max:255',
            'contact' => ['required', 'string', 'max:32', 'regex:/^[0-9+()\-\s]{7,32}$/'],
            'currency' => ['required', Rule::in(['INR', 'USD'])], 'amount' => 'required|numeric|min:1|max:1000000',
            'receipt' => 'nullable|string|max:100']);
        if (!$this->razorpay->configured()) return response()->json(['message' => 'Razorpay is currently unavailable.'], 503);
        $minor = (int) round(((float) $data['amount']) * 100);
        $payment = $this->pending(['provider' => 'razorpay', 'amount_minor' => $minor, 'total_minor' => $minor,
            'currency' => $data['currency'], 'payer_name' => $data['name'], 'payer_email' => $data['email'],
            'payer_contact' => $data['contact'], 'receipt' => $data['receipt'] ?? null]);
        try {
            $order = $this->razorpay->createOrder(['receipt' => $payment->reference, 'amount' => $minor,
                'currency' => $data['currency'], 'payment_capture' => 1]);
            $payment->update(['provider_order_id' => $order['id'], 'gateway_payload' => $this->safePayload($order)]);
            $request->session()->put('razorpay_payments.'.$payment->reference, now()->timestamp);
            return response()->json(['order_id' => $order['id'], 'key' => config('services.razorpay.key_id'),
                'amount' => $minor, 'currency' => $data['currency'], 'reference' => $payment->reference,
                'prefill' => ['name' => $data['name'], 'email' => $data['email'], 'contact' => $data['contact']]]);
        } catch (Throwable $e) {
            $payment->update(['status' => 'failed']); report($e);
            return response()->json(['message' => 'Unable to start Razorpay checkout. Please try again.'], 502);
        }
    }

    public function razorpaySuccess(Request $request)
    {
        $data = $request->validate(['razorpay_payment_id' => 'required|string|max:255',
            'razorpay_order_id' => 'required|string|max:255', 'razorpay_signature' => 'required|string|max:500',
            'reference' => 'required|uuid']);
        try {
            $this->razorpay->verifySignature($data);
            $gatewayOrder = $this->razorpay->fetchOrder($data['razorpay_order_id']);
            $gatewayPayment = $this->razorpay->fetchPayment($data['razorpay_payment_id']);
            $payment = DB::transaction(function () use ($data, $gatewayOrder, $gatewayPayment) {
                $payment = Payment::where('reference', $data['reference'])->lockForUpdate()->firstOrFail();
                if ($payment->status === 'paid') return $payment;
                $valid = $payment->provider === 'razorpay'
                    && hash_equals((string) $payment->provider_order_id, (string) $data['razorpay_order_id'])
                    && ($gatewayPayment['order_id'] ?? null) === $data['razorpay_order_id']
                    && (int) ($gatewayOrder['amount'] ?? -1) === $payment->total_minor
                    && (int) ($gatewayPayment['amount'] ?? -1) === $payment->total_minor
                    && strtoupper((string) ($gatewayOrder['currency'] ?? '')) === $payment->currency
                    && strtoupper((string) ($gatewayPayment['currency'] ?? '')) === $payment->currency
                    && in_array($gatewayPayment['status'] ?? '', ['captured', 'authorized'], true);
                if (!$valid) throw new \RuntimeException('Gateway payment details did not match the pending transaction.');
                $payment->update(['provider_payment_id' => $data['razorpay_payment_id'], 'status' => 'paid',
                    'paid_at' => now(), 'gateway_payload' => $this->safePayload($gatewayPayment)]);
                return $payment;
            });
            return view('pages.payment-result', $this->sidebar->get() + ['payment' => $payment, 'successful' => true]);
        } catch (Throwable $e) {
            report($e); return response()->view('pages.payment-result', $this->sidebar->get() + ['successful' => false, 'reference' => $data['reference']], 422);
        }
    }

    public function razorpayAbandon(Request $request): JsonResponse
    {
        $data = $request->validate(['reference' => 'required|uuid',
            'status' => ['required', Rule::in(['failed', 'cancelled'])], 'reason' => 'nullable|string|max:160']);
        $started = $request->session()->get('razorpay_payments.'.$data['reference']);
        if (!$started || now()->timestamp - $started > 7200) return response()->json(['message' => 'Payment session expired.'], 403);
        $payment = DB::transaction(function () use ($data) {
            $payment = Payment::where('reference', $data['reference'])->lockForUpdate()->firstOrFail();
            if ($payment->provider === 'razorpay' && $payment->status === 'pending') {
                $payment->update(['status' => $data['status'],
                    'gateway_payload' => ['reason' => Str::limit($data['reason'] ?? $data['status'], 160, '')]]);
            }
            return $payment;
        });
        if ($payment->status === 'paid') return response()->json(['message' => 'This payment is already complete.'], 409);
        return response()->json(['redirect' => route('payment.result', ['reference' => $payment->reference])]);
    }

    public function paypalOrder(Request $request): JsonResponse
    {
        $data = $request->validate(['quote' => 'required|uuid']);
        $quote = $request->session()->get('payment_quotes.'.$data['quote']);
        if (!$quote || !in_array((int) ($quote['fee'] ?? 0), self::PAYPAL_FEES, true) || now()->timestamp - ($quote['created_at'] ?? 0) > 3600) return response()->json(['message' => 'Payment quote expired.'], 422);
        if (!$this->paypal->configured()) return response()->json(['message' => 'PayPal is currently unavailable.'], 503);
        $base = (int) $quote['fee'] * 100; $tax = (int) round($base * .10); $total = $base + $tax;
        $payment = $this->pending(['provider' => 'paypal', 'amount_minor' => $total, 'base_minor' => $base,
            'tax_minor' => $tax, 'total_minor' => $total, 'currency' => 'USD']);
        try {
            $order = $this->paypal->createOrder($payment->reference, $total);
            if (empty($order['id'])) throw new \RuntimeException('PayPal order ID missing.');
            $payment->update(['provider_order_id' => $order['id'], 'gateway_payload' => $this->safePayload($order)]);
            return response()->json(['id' => $order['id'], 'reference' => $payment->reference]);
        } catch (Throwable $e) {
            $payment->update(['status' => 'failed']); report($e);
            return response()->json(['message' => 'Unable to start PayPal checkout. Please try again.'], 502);
        }
    }

    public function paypalCapture(Request $request): JsonResponse
    {
        $data = $request->validate(['order_id' => 'required|string|max:255', 'reference' => 'required|uuid']);
        try {
            $existing = Payment::where('reference', $data['reference'])->where('provider_order_id', $data['order_id'])->first();
            if ($existing?->status === 'paid') return response()->json(['redirect' => route('payment.result', ['reference' => $existing->reference])]);
            $result = $this->paypal->captureOrder($data['order_id']);
            $payment = DB::transaction(function () use ($data, $result) {
                $payment = Payment::where('reference', $data['reference'])->lockForUpdate()->firstOrFail();
                if ($payment->status === 'paid') return $payment;
                $capture = data_get($result, 'purchase_units.0.payments.captures.0', []);
                $valid = $payment->provider === 'paypal' && $payment->provider_order_id === $data['order_id']
                    && ($result['id'] ?? null) === $data['order_id'] && ($result['status'] ?? null) === 'COMPLETED'
                    && ($capture['status'] ?? null) === 'COMPLETED'
                    && strtoupper((string) data_get($capture, 'amount.currency_code')) === 'USD'
                    && (int) round(((float) data_get($capture, 'amount.value', -1)) * 100) === $payment->total_minor;
                if (!$valid) throw new \RuntimeException('PayPal capture was incomplete or mismatched.');
                $payment->update(['provider_payment_id' => $capture['id'] ?? null, 'status' => 'paid', 'paid_at' => now(),
                    'payer_name' => trim(data_get($result, 'payer.name.given_name', '').' '.data_get($result, 'payer.name.surname', '')),
                    'payer_email' => data_get($result, 'payer.email_address'), 'gateway_payload' => $this->safePayload($result)]);
                return $payment;
            });
            return response()->json(['redirect' => route('payment.result', ['reference' => $payment->reference])]);
        } catch (Throwable $e) {
            report($e); return response()->json(['message' => 'Payment could not be verified. No confirmation has been recorded.'], 422);
        }
    }

    public function result(string $reference)
    {
        $payment = Payment::where('reference', $reference)->firstOrFail();
        return view('pages.payment-result', $this->sidebar->get() + ['payment' => $payment,
            'reference' => $payment->reference, 'successful' => $payment->status === 'paid']);
    }

    private function pending(array $attributes): Payment
    {
        $attributes += ['reference' => Str::uuid()->toString(), 'status' => 'pending'];
        $table = (new Payment)->getTable();
        if (Schema::hasColumn($table, 'amount')) $attributes['amount'] = number_format($attributes['total_minor'] / 100, 2, '.', '');
        if (Schema::hasColumn($table, 'order_details')) $attributes['order_details'] = '{}';
        if (Schema::hasColumn($table, 'payment_details')) $attributes['payment_details'] = '{}';
        return Payment::create($attributes);
    }

    private function safePayload(array $payload): array
    {
        return collect($payload)->only(['id', 'entity', 'amount', 'currency', 'status', 'receipt', 'created_at'])->all();
    }
}
