<?php

namespace App\Services;

use RuntimeException;

class PayPalApiException extends RuntimeException
{
    public function __construct(public readonly ?string $issue, public readonly ?string $debugId, int $status, string $body)
    {
        parent::__construct('PayPal API error '.$status.' ['.($issue ?? 'unknown').'] debug_id='.($debugId ?? 'n/a').': '.mb_substr($body, 0, 1000), $status);
    }
}
