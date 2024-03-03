<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Favorite;
use App\Services\GenerateResArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function store($slug): JsonResponse
    {
        $article = Article::where('slug', $slug)->first();
        $article_id = $article->id;
        $user = auth()->user();
        $user_id = $user->id;
        Favorite::create([
            'article_id' => $article_id,
            'user_id' => $user_id
        ]);

        $responseData = GenerateResArticleService::generateResArticle($article);

        return response()->json([
            'article' => $responseData
        ]);
    }

    public function delete($slug): JsonResponse
    {
        $article = Article::where('slug', $slug)->first();
        $article_id = $article->id;
        $user = auth()->user();
        $user_id = $user->id;
        $favorite = Favorite::where('user_id', $user_id)
            ->where('article_id', $article_id)
            ->first();

        $favorite->delete();

        return response()->json([
            'article' => [
                $favorite
            ]
        ]);
    }
}
