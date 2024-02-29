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
        'slug',
        'description',
        'body'
    ];
}

// タグ情報たちを取得する
// そのタグ情報をもとにデータを取得する
// ・データが取得できなかったら新しくタグテーグルにデータを挿入する
//  そのデータを作成したら、idを取得して中間テーブルに保存していく
// ・データが取得できたら、中間テーブルに保存していく

// 上記を実装するために
// ・タグテーブルを作成する
//  id,tagName,
//  .中感テーブルを作成する
//  post_id、tag_idを保存する
