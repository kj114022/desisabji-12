<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
       
            return [
                "description" => $this->faker->sentence,
                "address" => $this->faker->address,
                "latitude" => $this->faker->latitude,
                "longitude" => $this->faker->longitude,
                "is_default" => $this->faker->boolean,
                "user_id" => $this->faker->numberBetween(1,6),
                ];
      
    }
}
