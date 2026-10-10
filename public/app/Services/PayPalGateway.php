<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PayPalGateway
{
    public function configured(): bool
    {
        return filled(config('services.paypal.client_id')) && filled(config('services.paypal.client_secret'));
    }

    private function baseUrl(): string
    {
        return config('services.paypal.mode') === 'live' ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';
    }

    private function client(): PendingRequest
    {
        $token = Http::asForm()->withBasicAuth((string) config('services.paypal.client_id'), (string) config('services.paypal.client_secret'))
            ->post($this->baseUrl().'/v1/oauth2/token', ['grant_type' => 'client_credentials'])
            ->throw()->json('access_token');
        if (!$token) throw new RuntimeException('PayPal access token was not returned.');
        return Http::withToken($token)->acceptJson();
    }

    public function createOrder(string $reference, int $totalMinor): array
    {
        return $this->client()->post($this->baseUrl().'/v2/checkout/orders', [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => $reference,
                'amount' => ['currency_code' => 'USD', 'value' => number_format($totalMinor / 100, 2, '.', '')],
            ]],
        ])->throw()->json();
    }

    public function captureOrder(string $orderId): array
    {
        return $this->client()->withHeaders(['PayPal-Request-Id' => 'isar-capture-'.$orderId])
            ->post($this->baseUrl().'/v2/checkout/orders/'.$orderId.'/capture')->throw()->json();
    }
}
