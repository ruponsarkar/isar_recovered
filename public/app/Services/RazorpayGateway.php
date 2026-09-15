<?php

namespace App\Services;

use Razorpay\Api\Api;

class RazorpayGateway
{
    private function api(): Api
    {
        return new Api((string) config('services.razorpay.key_id'), (string) config('services.razorpay.key_secret'));
    }

    public function configured(): bool
    {
        return filled(config('services.razorpay.key_id')) && filled(config('services.razorpay.key_secret'));
    }

    public function createOrder(array $attributes): array
    {
        return $this->api()->order->create($attributes)->toArray();
    }

    public function verifySignature(array $attributes): void
    {
        $this->api()->utility->verifyPaymentSignature($attributes);
    }

    public function fetchOrder(string $id): array
    {
        return $this->api()->order->fetch($id)->toArray();
    }

    public function fetchPayment(string $id): array
    {
        return $this->api()->payment->fetch($id)->toArray();
    }
}
