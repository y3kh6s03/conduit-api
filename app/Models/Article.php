<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    // const CREATED_AT = 'createdAt';
    // const UPDATED_AT = 'updatedAt';

    // protected $dateFormat = 'U';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'body'
    ];
}


// タグをつけなければならない
// このリクエストを確認してどのようにタグを取得しようとしているか確認しよう