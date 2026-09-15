<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'public_payments';

    protected $fillable = [
        'provider', 'provider_order_id', 'provider_payment_id', 'amount_minor',
        'currency', 'base_minor', 'tax_minor', 'total_minor', 'payer_name',
        'payer_email', 'payer_contact', 'reference', 'receipt', 'status',
        'gateway_payload', 'paid_at', 'amount', 'order_details', 'payment_details',
    ];

    protected $casts = [
        'gateway_payload' => 'array',
        'paid_at' => 'datetime',
        'amount_minor' => 'integer',
        'base_minor' => 'integer',
        'tax_minor' => 'integer',
        'total_minor' => 'integer',
    ];
}
