<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FaqFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
       
        return [
            'question' => $this->faker->text(100),
            'answer' => $this->faker->realText(),
            'faq_category_id' => $this->faker->numberBetween(1, 4)
        ];
      
    }
}
