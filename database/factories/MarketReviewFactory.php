<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\MarketReview;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MarketReview>
 */
class MarketReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MarketReview::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        // Use dynamic IDs from existing records
        $userIds = \App\Models\User::pluck('id')->toArray();
        $marketIds = \App\Models\Market::pluck('id')->toArray();
        
        return [
            'review' => $this->faker->realText(),
            'rate' => $this->faker->randomFloat(2, 1, 5),
            'user_id' => !empty($userIds) ? $this->faker->randomElement($userIds) : 1,
            'market_id' => !empty($marketIds) ? $this->faker->randomElement($marketIds) : 1,
        ];
    }
    public function unverified(): static
    {
        //return $this;
    }
}