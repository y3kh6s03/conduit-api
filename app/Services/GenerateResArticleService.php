<?php

namespace App\Services;

use App\Models\Article;

class GenerateResArticleService
{
    public static function generateResArticle($article)
    {
        $article_id = $article->id;
        $tagJsons = Article::find($article_id)->tags()->orderBy('name')->get();
        $tags = json_decode($tagJsons, true);
        $tagNames = array_column($tags, 'name');
        $article['tagList'] = $tagNames;
        $user=Article::find($article_id)->user()->first();
        $username=$user->username;
        $article['author'] = $username;
        $responseArticle = $article;

        return $responseArticle;
    }
}
