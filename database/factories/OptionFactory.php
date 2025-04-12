<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
       
            return [
                'name' => $this->faker->randomElement(['XL','L','S','Green','Red','White','5L','2L','500g','1Kg','Tomato','Oil']),
                'description' => $this->faker->sentence(4),
                'price' => $this->faker->randomFloat(2,10,50),
                'product_id' => $this->faker->numberBetween(1,30),
                'option_group_id' => $this->faker->numberBetween(1,4),
                ];
      
    }
}
