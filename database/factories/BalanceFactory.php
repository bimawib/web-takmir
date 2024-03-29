<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Balance>
 */
class BalanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $is_spend_rand = rand(0,1);

        if($is_spend_rand == 1){
            $spend_rand = $this->faker->numberBetween(5000,500000);
            $income_rand = 0;
        } else {
            $spend_rand = 0;
            $income_rand = $this->faker->numberBetween(5000,500000);
        }

        return [
            'user_id'=>$this->faker->numberBetween(1,3),
            'title'=>fake()->word(),
            'is_spend'=>$is_spend_rand,
            'spend_balance'=>$spend_rand,
            'incoming_balance'=>$income_rand,
            'total_balance'=>$this->faker->numberBetween(500000,5000000),
            'note'=>$this->faker->text(13),
            'date'=>$this->faker->date()
        ];
    }
}