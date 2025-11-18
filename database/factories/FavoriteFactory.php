<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Favorite;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Favorite>
 */
class FavoriteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Favorite::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        // Use dynamic IDs from existing records
        $productIds = \App\Models\Product::pluck('id')->toArray();
        $userIds = \App\Models\User::pluck('id')->toArray();
        
        return [
            'product_id' => !empty($productIds) ? $this->faker->randomElement($productIds) : 1,
            'user_id' => !empty($userIds) ? $this->faker->randomElement($userIds) : 1
        ];
    }

    public function unverified(): static
    {
        
    }
}
