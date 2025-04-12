<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SlideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
       
        $product = $this->faker->boolean;
        $array = [
            'order' => $this->faker->numberBetween(0, 5),
            'text' => $this->faker->sentence(4),
            'button' => $this->faker->randomElement(['Discover It', 'Order Now', 'Get Discount']),
            'text_position' => $this->faker->randomElement(['start', 'end', 'center']),
            'text_color' => '#25d366',
            'button_color' => '#25d366',
            'background_color' => '#ccccdd',
            'indicator_color' => '#25d366',
            'image_fit' => 'cover',
            'product_id' => $product ? $this->faker->numberBetween(1, 15) : null,
            'market_id' => !$product ? $this->faker->numberBetween(1, 4) : null,
            'enabled' => $this->faker->boolean,
        ];
    
        return $array;
      
    }
}