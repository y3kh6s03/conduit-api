<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleTag;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function store(Request $req)
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

    public function show($slug)
    {
        $article = Article::where('slug', $slug)->first();
        $article_id = $article->id;

        $tags = ArticleTag::where('article_id', $article_id)->get();
        foreach($tags as $tag){
            $tagData=Tag::find($tag->tag_id);
            $tagNames[]=$tagData['name'];
        }
        $user = auth()->user();
        $article['tagList']=$tagNames;
        $article['author']=$user;
        return response()->json([
            "article" => $article
        ]);
    }
}


// タグリストを取得する
// articleのidからpost_tagテーブルから取得できる