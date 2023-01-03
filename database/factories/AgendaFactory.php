<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Agenda>
 */
class AgendaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id'=>$this->faker->unique()->numberBetween(1,3),
            'title'=>$this->faker->sentence(3),
            'slug'=>$this->faker->unique()->slug(3),
            'image'=>$this->faker->imageUrl(360, 360, 'animals', true, 'cats'),
            'location'=>$this->faker->word(),
            // 'date'=>$this->faker->date('d-m-Y'),
            'date'=>$this->faker->date(),
            'published_at'=>$this->faker->date()
        ];
    }
}
