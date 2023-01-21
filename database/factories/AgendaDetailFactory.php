<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AgendaDetail>
 */
class AgendaDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'agenda_id'=>$this->faker->numberBetween(1,3),
            'agenda_name'=>$this->faker->word(),
            'start_time'=>$this->faker->time('H:i'),
            'end_time'=>$this->faker->time('H:i'),
            'location'=>$this->faker->word(),
            'keynote_speaker'=>fake()->name(),
            'note'=>'sediakan jajan',
        ];
    }
}
