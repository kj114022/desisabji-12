<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Faq;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faq>
 */
class FaqFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Faq::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        // Use dynamic IDs from existing records
        $faqCategoryIds = \App\Models\FaqCategory::pluck('id')->toArray();
        
        return [
            'question' => $this->faker->text(100),
            'answer' => $this->faker->realText(),
            'faq_category_id' => !empty($faqCategoryIds) ? $this->faker->randomElement($faqCategoryIds) : 1
        ];
    }
    public function unverified(): static
    {
        
    }
}
