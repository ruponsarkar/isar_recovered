<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class journal extends Model
{
    use HasFactory;

    protected $primaryKey = 'j_id';

    protected static function booted()
    {
        static::created(function (self $journal) {
            app(\App\Services\JournalPageDefaults::class)->seed((int) $journal->j_id);
        });
    }
}
