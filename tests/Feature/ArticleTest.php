<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_single_article(): void
    {
        $article=Article::factory()->create();
        $slug=$article->slug;
        $res = $this->get('api/articles/'.$slug);
        $res->assertStatus(200);
    }

}
