# Factory Documentation - DesiSabji Services

## Overview

This document explains the factory system used in DesiSabji Services, including Faker usage, alternatives, and best practices.

## Why Faker is Used

**Faker** (via `fakerphp/faker`) is the **industry standard** for generating fake data in Laravel applications. It's:

1. **Officially Recommended**: Laravel's documentation and best practices recommend Faker
2. **Comprehensive**: Provides 100+ methods for generating realistic fake data
3. **Localized**: Supports multiple languages and locales
4. **Well-Maintained**: Actively maintained with regular updates
5. **Performance**: Optimized for generating large amounts of data

## Current Implementation

### Factory Structure

All factories follow Laravel 12 conventions:

```php
<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Salad', 'Sandwich', 'Bread']),
            'price' => $this->faker->randomFloat(2, 10, 50),
            'description' => $this->faker->text,
        ];
    }
}
```

### Dynamic Foreign Key Relationships

**Key Improvement**: All factories now use **dynamic foreign key relationships** instead of hardcoded IDs:

```php
// ❌ OLD (Hardcoded - causes foreign key errors)
'category_id' => $this->faker->numberBetween(1, 6),

// ✅ NEW (Dynamic - uses actual existing IDs)
$categoryIds = \App\Models\Category::pluck('id')->toArray();
'category_id' => !empty($categoryIds) ? $this->faker->randomElement($categoryIds) : 1,
```

This ensures:
- **No foreign key constraint violations**
- **Works with any database state**
- **Respects actual data relationships**

## Faker Alternatives (Not Recommended)

While alternatives exist, they're **not recommended** for production Laravel applications:

### 1. Static Arrays
```php
// Limited to predefined values
'name' => ['Value1', 'Value2', 'Value3'][array_rand(['Value1', 'Value2', 'Value3'])],
```
**Issues**: Limited variety, not realistic, requires manual maintenance

### 2. Laravel Str::random()
```php
'name' => Str::random(10),
```
**Issues**: Generates random strings, not realistic data

### 3. mt_rand() / random_int()
```php
'price' => mt_rand(10, 50),
```
**Issues**: Only for numbers, no formatting, not realistic

### 4. Custom Helper Functions
```php
'email' => 'user' . rand(1, 1000) . '@example.com',
```
**Issues**: Requires custom code, less flexible, harder to maintain

## Best Practices

### 1. Use Faker for Realistic Data
```php
// ✅ Good
'email' => $this->faker->unique()->safeEmail(),
'address' => $this->faker->address,
'phone' => $this->faker->phoneNumber,

// ❌ Avoid
'email' => 'test' . rand() . '@test.com',
'address' => '123 Main St',
```

### 2. Use Dynamic Relationships
```php
// ✅ Good - Dynamic
$categoryIds = \App\Models\Category::pluck('id')->toArray();
'category_id' => !empty($categoryIds) ? $this->faker->randomElement($categoryIds) : 1,

// ❌ Bad - Hardcoded
'category_id' => $this->faker->numberBetween(1, 6),
```

### 3. Use Factory States for Variations
```php
public function featured(): static
{
    return $this->state(fn (array $attributes) => [
        'featured' => true,
    ]);
}

// Usage
Product::factory()->featured()->create();
```

### 4. Use Relationships in Factories
```php
// ✅ Good - Creates related models
Product::factory()
    ->has(Option::factory()->count(3))
    ->create();

// ❌ Avoid - Manual relationship setup
$product = Product::factory()->create();
Option::factory()->create(['product_id' => $product->id]);
```

## Common Faker Methods

### Text & Strings
```php
$this->faker->word()                    // Single word
$this->faker->sentence()                // Sentence
$this->faker->paragraph()               // Paragraph
$this->faker->text(200)                 // Text with max length
$this->faker->realText()                // Realistic text
```

### Numbers
```php
$this->faker->randomNumber()            // Random number
$this->faker->randomFloat(2, 10, 50)   // Float with 2 decimals, min 10, max 50
$this->faker->numberBetween(1, 100)    // Integer between range
```

### Dates
```php
$this->faker->date()                    // Date string
$this->faker->dateTime()                // DateTime object
$this->faker->dateTimeBetween('-1 year', 'now') // Date range
```

### Internet
```php
$this->faker->email()                   // Email address
$this->faker->url()                     // URL
$this->faker->ipv4()                    // IP address
```

### Address
```php
$this->faker->address()                 // Full address
$this->faker->city()                    // City name
$this->faker->country()                 // Country name
$this->faker->latitude()                // Latitude
$this->faker->longitude()               // Longitude
```

### Boolean & Random Selection
```php
$this->faker->boolean()                 // true or false
$this->faker->randomElement(['A', 'B', 'C']) // Random from array
```

## Performance Considerations

### Batch Creation
```php
// ✅ Efficient - Creates 100 products in one query
Product::factory()->count(100)->create();

// ❌ Inefficient - 100 separate queries
for ($i = 0; $i < 100; $i++) {
    Product::factory()->create();
}
```

### Lazy Evaluation
```php
// ✅ Good - Only generates when needed
Product::factory()->count(1000)->lazy()->each(function ($product) {
    // Process each product
});

// ❌ Avoid - Loads all into memory
Product::factory()->count(1000)->create(); // All in memory
```

## Troubleshooting

### Issue: Foreign Key Constraint Violations
**Solution**: Use dynamic IDs from existing records
```php
$categoryIds = \App\Models\Category::pluck('id')->toArray();
'category_id' => !empty($categoryIds) ? $this->faker->randomElement($categoryIds) : 1,
```

### Issue: Faker is null
**Solution**: Ensure `fakerphp/faker` is installed
```bash
composer require fakerphp/faker --dev
```

### Issue: Factory not found
**Solution**: Ensure model has `HasFactory` trait
```php
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
}
```

## Migration from Hardcoded to Dynamic

All factories have been updated to use dynamic foreign keys:

- ✅ ProductFactory - Uses dynamic category_id and market_id
- ✅ OptionFactory - Uses dynamic product_id and option_group_id
- ✅ DeliveryAddressFactory - Uses dynamic user_id
- ✅ FavoriteFactory - Uses dynamic product_id and user_id
- ✅ ProductReviewFactory - Uses dynamic user_id and product_id
- ✅ MarketReviewFactory - Uses dynamic user_id and market_id
- ✅ SlideFactory - Uses dynamic product_id and market_id
- ✅ FaqFactory - Uses dynamic faq_category_id
- ✅ GalleryFactory - Uses dynamic market_id

## Conclusion

**Faker is the recommended and professional approach** for generating fake data in Laravel. The current implementation:

1. ✅ Uses Faker for realistic data generation
2. ✅ Uses dynamic foreign keys to prevent constraint violations
3. ✅ Follows Laravel 12 best practices
4. ✅ Is maintainable and scalable

**No changes needed** - the current implementation is production-ready and follows industry standards.

