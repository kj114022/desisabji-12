<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MarketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
       
            return [
                'name' => $this->faker->randomElement(['Shop','Grocery','Market','Pharmacy','Mall','Furniture'])." ".$this->faker->company,
                'description' => $this->faker->text,
                'address' => $this->faker->address,
                'latitude' => $this->faker->randomFloat(6, 55, 37),
                'longitude' => $this->faker->randomFloat(6, 12, 7),
                'phone' => $this->faker->phoneNumber,
                'mobile' => $this->faker->phoneNumber,
                'information' => $this->faker->sentences(3,true),
                'admin_commission' => $this->faker->randomFloat(2,10,50),
                'delivery_fee' => $this->faker->randomFloat(2,1,10),
                'delivery_range' => $this->faker->randomFloat(2,5,100),
                'default_tax' => $this->faker->randomFloat(2,5,30), //added
                'minimum_order' => $this->faker->randomFloat(2,5,30), //added    
                'closed' => $this->faker->boolean,
                'active' => 1,
                'available_for_delivery' => $this->faker->boolean,
                ];
      
    }
}
