<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GalleryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
       
            return [
                'description' => $this->faker->sentence,
                'market_id' => $this->faker->numberBetween(1, 10)
                ];
      
    }
}
