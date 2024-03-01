<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleTag;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use App\Services\GenerateResArticleService;

class ArticleController extends Controller
{
    public function store(Request $req): JsonResponse
    {
        $req->validate([
            'article.title' => ['required', 'string', 'min:10', 'max:255'],
            'article.description' => ['required', 'string', 'min:10', 'max:255'],
            'article.body' => ['required'],
        ]);

        $jsonData = $req->json()->all();
        $title = $jsonData['article']['title'];
        $slug = Str::slug($title);
        $description = $jsonData['article']['description'];
        $body = $jsonData['article']['body'];
        $user = auth()->user();
        $user_id = $user->id;
        $article = Article::create([
            'user_id' => $user_id,
            'title' => $title,
            'slug' => $slug,
            'description' => $description,
            'body' => $body,
        ]);

        $tagNames = $jsonData['article']['tagList'];
        foreach ($tagNames as $tagName) {
            $tagData = Tag::firstOrCreate([
                'name' => $tagName
            ]);
            $articleTags[] = ArticleTag::create([
                'article_id' => $article->id,
                'tag_id' => $tagData->id,
            ]);
        }

        $responseData = GenerateResArticleService::generateResArticle($article);

        return response()->json([
            "article" => $responseData
        ]);
    }

    public function show($slug): JsonResponse
    {
        $article = Article::where('slug', $slug)->first();
        $responseData = GenerateResArticleService::generateResArticle($article);

        return response()->json([
            "article" => $responseData
        ]);
    }

    public function update(Request $req, $slug): JsonResponse
    {
        $updateJsonData = $req->json()->all();
        $updateColumn = key($updateJsonData['article']);
        $data = $updateJsonData['article'][$updateColumn];
        $article = Article::where('slug', $slug)->first();
        $article->$updateColumn = $data;
        $article->save();
        $responseData = GenerateResArticleService::generateResArticle($article);

        return response()->json([
            "article" => $responseData
        ]);
    }

    public function delete($slug): Response
    {
        $article = Article::where('slug', $slug)->first();
        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }
        $article->tags()->detach();
        $article->delete();
        return response()->noContent();
    }
}
