<?php

namespace Database\Factories;

use App\Models\Blog;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id'=>$this->faker->numberBetween(1,13),
            'slug'=>$this->faker->slug(3),
            'title'=>$this->faker->sentence(3),
            'body'=>$this->faker->paragraph(5),
            'image'=>$this->faker->imageUrl(360, 360, 'animals', true, 'cats'),
            'is_verified'=>$this->faker->boolean(),
            'published_at'=>$this->faker->date()
        ];
    }
}
// kalo mau tambah bikin array baru aja