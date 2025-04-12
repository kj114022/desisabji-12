<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
       
        return [
            "review" => $this->faker->realText(),
            "rate" => $this->faker->randomFloat(2,1,5),
            "user_id" => $this->faker->numberBetween(1,6),
            "product_id" => $this->faker->numberBetween(1,30),
        ];
      
    }
}
