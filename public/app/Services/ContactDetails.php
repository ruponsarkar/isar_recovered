<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;

class ContactDetails
{
    public const DEFAULTS = [
        'address' => 'H.No. 61, Ward No. 4, Bokakhat, Golaghat, Assam, India, 785612',
        'email' => 'contact@isarpublisher.com',
        'phone' => '+91 8638994354',
    ];

    public function stored(int $journalId): array
    {
        try {
            if (!Schema::hasTable('contact_details')) return [];
            return (array) (DB::table('contact_details')->where('journal_id', $journalId)->first(['address', 'email', 'phone']) ?: []);
        } catch (\Illuminate\Database\QueryException $e) {
            // API-only public installations may not have a local database.
            return [];
        }
    }

    private array $resolved = [];

    public function resolve(int $journalId = 0): array
    {
        if (isset($this->resolved[$journalId])) return $this->resolved[$journalId];
        $base = rtrim((string) config('services.public_api.base_url'), '/');
        if ($base) {
            try {
                $response = Http::connectTimeout(3)->timeout(5)->acceptJson()->get($base.'/contact-details/'.($journalId ?: 'default'));
                $contact = $response->json('contact');
                if ($response->successful() && is_array($contact)) {
                    return $this->resolved[$journalId] = array_replace(self::DEFAULTS, array_intersect_key($contact, self::DEFAULTS));
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                // Existing contacts remain available if the API is temporarily unavailable.
            }
        }
        return $this->resolved[$journalId] = $this->local($journalId);
    }

    private function local(int $journalId): array
    {
        $filled = fn ($values) => array_filter($values, fn ($value) => $value !== null && trim($value) !== '');
        $defaults = array_replace(self::DEFAULTS, $filled($this->stored(0)));
        return $journalId ? array_replace($defaults, $filled($this->stored($journalId))) : $defaults;
    }
}
