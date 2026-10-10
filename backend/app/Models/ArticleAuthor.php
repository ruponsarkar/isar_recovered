<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleAuthor extends Model
{
    protected $fillable = ['article_id', 'first_name', 'last_name', 'designation', 'affiliation', 'corresponding', 'super_no', 'position'];
    protected $casts = ['corresponding'=>'boolean', 'super_no'=>'integer'];
}
