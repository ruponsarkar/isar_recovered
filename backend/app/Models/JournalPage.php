<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalPage extends Model
{
    protected $fillable = ['title', 'slug', 'type', 'system_key', 'content', 'link_url', 'parent_id', 'sort_order', 'is_published', 'show_in_nav', 'show_in_tabs'];

    protected $casts = [
        'journal_id' => 'integer', 'parent_id' => 'integer', 'sort_order' => 'integer',
        'is_published' => 'boolean', 'show_in_nav' => 'boolean', 'show_in_tabs' => 'boolean',
    ];
}
