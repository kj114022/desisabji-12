<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Field;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Field>
 */
class FieldFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Field::class;

    private static int $i = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $names = ['Grocery','Pharmacy','Restaurant','Store','Electronics','Furniture'];
        $name = $names[self::$i % count($names)];
        self::$i++;
        return [
            'name' => $name,
            'description' => $this->faker->sentences(5, true),
        ];
    }

    public function unverified(): static
    {

    }
}
