<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Slide;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Slide>
 */
class SlideFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Slide::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        // Use dynamic IDs from existing records
        $productIds = \App\Models\Product::pluck('id')->toArray();
        $marketIds = \App\Models\Market::pluck('id')->toArray();
        $product = $this->faker->boolean;
        
        return [
            'order' => $this->faker->numberBetween(0, 5),
            'text' => $this->faker->sentence(4),
            'button' => $this->faker->randomElement(['Discover It', 'Order Now', 'Get Discount']),
            'text_position' => $this->faker->randomElement(['start', 'end', 'center']),
            'text_color' => '#25d366',
            'button_color' => '#25d366',
            'background_color' => '#ccccdd',
            'indicator_color' => '#25d366',
            'image_fit' => 'cover',
            'product_id' => $product && !empty($productIds) ? $this->faker->randomElement($productIds) : null,
            'market_id' => !$product && !empty($marketIds) ? $this->faker->randomElement($marketIds) : null,
            'enabled' => $this->faker->boolean,
        ];
    }
    public function unverified(): static
    {
        //return $this;
    }
}