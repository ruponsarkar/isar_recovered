<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ContactDetails
{
    public const DEFAULTS = [
        'address' => 'H.No. 61, Ward No. 4, Bokakhat, Golaghat, Assam, India, 785612',
        'email' => 'contact@isarpublisher.com',
        'phone' => '+91 8638994354',
    ];

    public function stored(int $journalId): array
    {
        if (!Schema::hasTable('contact_details')) return [];
        return (array) (DB::table('contact_details')->where('journal_id', $journalId)->first(['address', 'email', 'phone']) ?: []);
    }

    public function resolve(int $journalId = 0): array
    {
        $filled = fn ($values) => array_filter($values, fn ($value) => $value !== null && trim($value) !== '');
        $defaults = array_replace(self::DEFAULTS, $filled($this->stored(0)));
        return $journalId ? array_replace($defaults, $filled($this->stored($journalId))) : $defaults;
    }
}
