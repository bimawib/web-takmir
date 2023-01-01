<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lost>
 */
class LostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id'=>$this->faker->numberBetween(1,3),
            'title'=>$this->faker->sentence(2),
            'slug'=>$this->faker->slug(),
            'note'=>$this->faker->text(13),
            'contact'=>$this->faker->email(),
            'is_returned'=>$this->faker->boolean(),
            'date'=>$this->faker->date()
        ];
    }
}
// $table->foreignId('user_id');
//             $table->string('title');
//             $table->string('slug');
//             $table->string('note');
//             $table->string('contact');
//             $table->boolean('is_returned')->default(0);
//             $table->datetime('date');