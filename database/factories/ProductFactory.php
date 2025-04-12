<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
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
        $price = $this->faker->randomFloat(2,10,50);
        $discountPrice = $price - $this->faker->randomFloat(2,1,10);
        return [
            'code' => $this->faker->numberBetween(1,10),
            'name' => $this->faker->randomElement($products),
            'price' => $price,
            'discount_price' => $this->faker->randomElement([$discountPrice,0]),
            'description' => $this->faker->text,
            'capacity' => $this->faker->randomFloat(2,0.1,500),
            'package_items_count' => $this->faker->numberBetween(1,6),
            'unit' => $this->faker->randomElement(['L','g','Kg','Oz','ml','m','m²']),
            'featured' => $this->faker->boolean,
            'deliverable' =>  $this->faker->boolean,
            'market_id' => $this->faker->numberBetween(1,10),
            'category_id' => $this->faker->numberBetween(1,6),
        ];
      
    }
}
