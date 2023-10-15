<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->unique()->realText(30);
        return [
            'user_id' => rand(1,2),
            'title' => $title,
            'slug' => Str::slug($title),
            'body' => $this->faker->realText(30000),
            'image_cover' => env('APP_URL', 'http://127.0.0.1:8000') . "/storage/cover/image_cover_default.jpg",
        ];
    }
}
