<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\DeliveryAddress;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeliveryAddress>
 */
class DeliveryAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DeliveryAddress::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        // Use dynamic IDs from existing records
        $userIds = \App\Models\User::pluck('id')->toArray();
        
        return [
            'description' => $this->faker->sentence,
            'address' => $this->faker->address,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'is_default' => $this->faker->boolean,
            'user_id' => !empty($userIds) ? $this->faker->randomElement($userIds) : 1,
        ];
    }

     public function unverified(): static
    {
       
    }
}
