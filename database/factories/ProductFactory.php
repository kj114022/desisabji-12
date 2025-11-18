<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    { 
        $products = [
            'Salad',
            'Sandwich',
            'Bread',
            'Steak',
            'Tuna steak',
            'Fish',
            'Rice',
            'Spaghetti',
            'Cookie',
            'Cupcake',
            'Pasta',
            'Eggs',
            'Cheese',
            'Milk',
            'Sugar',
            'Vinegar',
            'Honey',
            'Salt',
            'Soup',
            'Onion',
            'Acarbose',
            'Aspirin',
        ];
        
        $price = $this->faker->randomFloat(2, 10, 50);
        $discountPrice = $price - $this->faker->randomFloat(2, 1, 10);
        
        // Use dynamic IDs from existing records
        $categoryIds = \App\Models\Category::pluck('id')->toArray();
        $marketIds = \App\Models\Market::pluck('id')->toArray();
        
        return [
            'name' => $this->faker->randomElement($products),
            'price' => $price,
            'discount_price' => $this->faker->randomElement([$discountPrice, 0]),
            'description' => $this->faker->text,
            'capacity' => $this->faker->randomFloat(2, 0.1, 500),
            'package_items_count' => $this->faker->numberBetween(1, 6),
            'unit' => $this->faker->randomElement(['L', 'g', 'Kg', 'Oz', 'ml', 'm', 'm²']),
            'featured' => $this->faker->boolean,
            'deliverable' => $this->faker->boolean,
            'market_id' => !empty($marketIds) ? $this->faker->randomElement($marketIds) : 1,
            'category_id' => !empty($categoryIds) ? $this->faker->randomElement($categoryIds) : 1,
        ];
    }


    public function unverified(): static
    {
        //return $this;
    }
}