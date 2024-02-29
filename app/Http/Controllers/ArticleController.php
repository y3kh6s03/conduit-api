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
            $tagData = Tag::where('name', $tagName)->first();
            if (!$tagData) {
                $createTag = Tag::create([
                    'name' => $tagName
                ]);
                $articleTags[] = ArticleTag::create([
                    'article_id' => $article->id,
                    'tag_id' => $createTag->id,
                ]);
            } else {
                $articleTags[] = ArticleTag::create([
                    'article_id' => $article->id,
                    'tag_id' => $tagData->id,
                ]);
            }
        }

        $article['tagList'] = $tagNames;
        $article['author'] = $user;


        return response()->json([
            "article" => $article
        ]);
    }

    public function show($slug): JsonResponse
    {
        $article = Article::where('slug', $slug)->first();
        $article_id = $article->id;

        $tags = ArticleTag::where('article_id', $article_id)->get();
        foreach ($tags as $tag) {
            $tagData = Tag::find($tag->tag_id);
            $tagNames[] = $tagData['name'];
        }
        $article['tagList'] = $tagNames;
        $user = auth()->user();
        $article['author'] = $user;
        return response()->json([
            "article" => $article
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

        $article_id = $article->id;

        $tags = ArticleTag::where('article_id', $article_id)->get();
        foreach ($tags as $tag) {
            $tagData = Tag::find($tag->tag_id);
            $tagNames[] = $tagData['name'];
        }
        $article['tagList'] = $tagNames;

        $user = auth()->user();
        $article['author'] = $user;

        return response()->json([
            "article" => $article
        ]);
    }

    public function delete($slug): Response
    {
        $article = Article::where('slug', $slug)->first();
        if(!$article){
            return response()->json(['message' => 'Article not found'], 404);
        }
        $article->tags()->detach();
        $article->delete();
        return response()->json([
            $article
        ]);
    }
}
