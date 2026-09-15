<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class article extends Model
{
    use HasFactory;
    protected $table = 'article';

    public function authors()
    {
        return $this->hasMany(ArticleAuthor::class, 'article_id')->orderBy('position');
    }
}
