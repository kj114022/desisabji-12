<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ProductReview;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductReview>
 */
class ProductReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductReview::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        // Use dynamic IDs from existing records
        $userIds = \App\Models\User::pluck('id')->toArray();
        $productIds = \App\Models\Product::pluck('id')->toArray();
        
        return [
            'review' => $this->faker->realText(),
            'rate' => $this->faker->randomFloat(2, 1, 5),
            'user_id' => !empty($userIds) ? $this->faker->randomElement($userIds) : 1,
            'product_id' => !empty($productIds) ? $this->faker->randomElement($productIds) : 1,
        ];
    }
    public function unverified(): static
    {
        //return $this;
    }
}