<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;

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
        $description = $jsonData['article']['description'];
        $body = $jsonData['article']['body'];
        $user = auth()->user();
        $user_id = $user['id'];

        $article = Article::create([
            'user_id' => $user_id,
            'title' => $title,
            'description' => $description,
            'body' => $body,
        ]);
        $article['slug'] = 'slugslugslug';

        return response()->json([
            "article" => $article
        ]);
    }
}


// $table->id();
// $table->string('title', 255);
// $table->string('description');
// $table->text('body');
// $table->timestamps();

// $table->unsignedBigInteger('user_id');
// $table->unsignedBigInteger('postTag_id');
// $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');