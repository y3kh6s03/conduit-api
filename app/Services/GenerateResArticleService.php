<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Favorite;

class GenerateResArticleService
{
    public static function generateResArticle($article): Article
    {
        $article_id = $article->id;
        $tagJsons = Article::find($article_id)->tags()->orderBy('name')->get();
        $tags = json_decode($tagJsons, true);
        $tagNames = array_column($tags, 'name');
        $article['tagList'] = $tagNames;
        $user = Article::find($article_id)->user()->first();
        $username = $user->username;
        $article['author'] = $username;
        $favorites = Favorite::where('article_id', $article_id)->get() ?? 0;
        $article['favorited'] = true;
        $article['favoritesCount'] = count($favorites);
        $responseArticle = $article;

        return $responseArticle;
    }
}
