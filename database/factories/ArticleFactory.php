<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();
        $user_id = $user->id;
        $title = fake()->sentence($nbWords = 6, $variableNbWords = true);
        $slug = Str::slug($title);
        return [
            'user_id' => $user_id,
            'title' => $title,
            'slug' => $slug,
            'description' => fake()->sentence($nbWords = 6, $variableNbWords = true),
            'body' => fake()->text($maxNbChars = 400)
        ];
    }
}
// 'user_id',
// 'title',
// 'slug',
// 'description',
// 'body'