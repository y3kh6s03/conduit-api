<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'body'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tags');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

