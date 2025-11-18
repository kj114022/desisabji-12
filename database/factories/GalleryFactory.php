<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Gallery;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gallery>
 */
class GalleryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Gallery::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        // Use dynamic IDs from existing records
        $marketIds = \App\Models\Market::pluck('id')->toArray();
        
        return [
            'description' => $this->faker->sentence,
            'market_id' => !empty($marketIds) ? $this->faker->randomElement($marketIds) : 1
        ];
    }
    public function unverified(): static
    {
        //return $this;
    }
}
