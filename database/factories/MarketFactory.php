<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Market;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Market>
 */
class MarketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Market::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
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
        'closed' => $this->faker->boolean,
        'active' => 1,
        'available_for_delivery' => $this->faker->boolean,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
       
    }
}
