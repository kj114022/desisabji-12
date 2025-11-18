<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Option;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Option>
 */
class OptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Option::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    { 
        // Use dynamic IDs from existing records
        $productIds = \App\Models\Product::pluck('id')->toArray();
        $optionGroupIds = \App\Models\OptionGroup::pluck('id')->toArray();
        
        return [
            'name' => $this->faker->randomElement(['XL', 'L', 'S', 'Green', 'Red', 'White', '5L', '2L', '500g', '1Kg', 'Tomato', 'Oil']),
            'description' => $this->faker->sentence(4),
            'price' => $this->faker->randomFloat(2, 10, 50),
            'product_id' => !empty($productIds) ? $this->faker->randomElement($productIds) : 1,
            'option_group_id' => !empty($optionGroupIds) ? $this->faker->randomElement($optionGroupIds) : 1,
        ];
    }

    public function unverified(): static
    {
        //return $this;
    }
}