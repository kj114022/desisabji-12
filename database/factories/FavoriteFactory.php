<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FavoriteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
       
        return [
            'product_city_id' => $this->faker->numberBetween(1, 30),
            'user_id' => $this->faker->numberBetween(1, 6)
        ];
      
    }
}
