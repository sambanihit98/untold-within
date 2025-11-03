<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $title = fake()->sentence(),
            'slug' => Str::slug($title) . '-' . fake()->unique()->randomNumber(5),
            'content' => fake()->paragraphs(3, true),
            'is_public' => fake()->boolean(80), // 80% chance true
            'views_count' => fake()->numberBetween(0, 500),
        ];
    }
}
