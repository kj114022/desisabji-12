# DesiSabji Laravel Project Documentation

This document outlines the setup, migration, and maintenance of the DesiSabji e-commerce project based on Laravel 12. It also includes a development journal tracking progress.

## Project Overview

DesiSabji is an e-commerce marketplace application allowing customers to browse products, place orders, and receive deliveries. Key features include user/product/order management, payment integration (PayPal, Stripe, RazorPay), delivery tracking, vendor management, and an admin dashboard.

## Technical Stack

- **Framework**: Laravel 12 (migrated from Laravel 8)
- **Database**: MySQL/MariaDB
- **Frontend Build Tool**: Vite (migrated from Laravel Mix)
- **Repository Pattern**: Custom implementation (replacing InfyOm)
- **PHP**: 8.2+

## Initial Setup

### Prerequisites

- PHP 8.2 or higher
- MariaDB 10.x
- Composer
- Node.js and npm

### Environment Setup

1.  **Clone:** `git clone [repository-url] && cd desisabji-12`
2.  **Install PHP Dependencies:** `composer install`
3.  **Install Node Dependencies:** `npm install`
4.  **Environment File:** `cp .env.example .env && php artisan key:generate`
5.  **Configure `.env`:** Set `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, and other necessary variables (payment keys, mail settings, etc.).
6.  **Database Setup:**
    ```sql
    -- Ensure MariaDB user has password auth & create DB
    SET PASSWORD FOR 'root'@'localhost' = PASSWORD('your_password');
    FLUSH PRIVILEGES;
    CREATE DATABASE algolive;
    ```
7.  **Migrate & Seed:** `php artisan migrate --seed` (See Journal for potential seeder issues)
8.  **Storage Link:** `php artisan storage:link`
9.  **Compile Assets:** `npm run dev` (development) or `npm run build` (production)
10. **Start Server:** `php artisan serve`

---

## Migration from Laravel 8 to Laravel 12

### Major Changes Made

1. **PHP Version Upgrade**:
   - Updated minimum PHP version requirement to 8.2

2. **Composer Dependencies**:
   - Updated `laravel/framework` to version 12.x
   - Removed unsupported packages like InfyOm Generator
   - Updated all other packages to compatible versions

3. **Frontend Assets**:
   - Replaced Laravel Mix with Vite
   - Created new `vite.config.js` file
   - Updated Blade directives from `@mix` to `@vite`

4. **Repository Pattern**:
   - Created a custom `BaseRepository` class to replace InfyOm's implementation
   - Updated 38+ repositories to use the new base repository

5. **Controller Routes**:
   - Converted string-based controller routes to array syntax:
     ```php
     // Old
     Route::get('/users', 'UserController@index');
     
     // New
     Route::get('/users', [UserController::class, 'index']);
     ```

6. **Eloquent Changes**:
   - Updated accessors/mutators to new syntax:
     ```php
     // Old
     public function getFirstNameAttribute($value) { ... }
     
     // New
     protected function firstName(): Attribute {
         return Attribute::make(
             get: fn ($value) => ucfirst($value),
             set: fn ($value) => strtolower($value),
         );
     }
     ```
   - Replaced `$dates` property with `$casts`

---

## Database Structure

The database consists of the following key tables:

- `users` - User accounts
- `markets` - Vendor markets/shops
- `products` - Products available for sale
- `categories` - Product categories
- `orders` - Customer orders
- `product_orders` - Pivot table linking products to orders
- `payments` - Payment records
- `delivery_addresses` - Customer delivery addresses
- `drivers` - Delivery personnel
- `carts` - Customer shopping carts
- `options` - Product options/variants
- `media` - Stores media files metadata
- `app_settings` - Application settings

---

## API Endpoints

The application provides a RESTful API for mobile applications:

1. **Authentication**:
   - `POST /api/login`
   - `POST /api/register`
   - `POST /api/logout`

2. **Products**:
   - `GET /api/products`
   - `GET /api/products/{id}`
   
3. **Orders**:
   - `GET /api/orders`
   - `POST /api/orders`
   - `GET /api/orders/{id}`

4. **Markets**:
   - `GET /api/markets`
   - `GET /api/markets/{id}`

---

## User Roles

The application has the following user roles:

1. `admin` - Full system access
2. `manager` - Manages markets and products
3. `client` - Regular customer
4. `driver` - Delivery personnel

---

## Maintenance Commands

- Clear cache:
  ```bash
  php artisan cache:clear
  php artisan config:clear
  php artisan route:clear
  php artisan view:clear
  ```

- Run tests:
  ```bash
  php artisan test
  ```

- Update database with new migrations:
  ```bash
  php artisan migrate
  ```

---

## Environmental Variables

Important environment variables:

- `APP_URL` - Application URL
- `DB_CONNECTION` - Database connection type
- `MAIL_MAILER` - Email service configuration
- `GOOGLE_MAPS_KEY` - For location features
- `STRIPE_KEY` / `STRIPE_SECRET` - Stripe payment gateway
- `PAYPAL_CLIENT_ID` / `PAYPAL_SECRET` - PayPal integration
- `RAZORPAY_KEY` / `RAZORPAY_SECRET` - RazorPay integration

---

## Common Issues and Troubleshooting

1. **MySQL Authentication Error**:
   ```
   ERROR 1698 (28000): Access denied for user 'root'@'localhost'
   ```
   Solution: Configure MariaDB to use password authentication:
   ```sql
   SET PASSWORD FOR 'root'@'localhost' = PASSWORD('your_password');
   FLUSH PRIVILEGES;
   ```

2. **Missing App Key**:
   ```
   No application encryption key has been specified.
   ```
   Solution: Generate a new application key:
   ```bash
   php artisan key:generate
   ```

3. **PHPUnit Schema Warning**:
   ```
   Your XML configuration validates against a deprecated schema.
   ```
   Solution: Migrate the configuration:
   ```bash
   phpunit --migrate-configuration
   ```

4. **Missing Repositories**:
   ```
   Class "InfyOm\Generator\Common\BaseRepository" not found
   ```
   Solution: Implement a custom BaseRepository class to replace the InfyOm one

---

## Future Improvements

1. Replace remaining InfyOm dependencies with Backpack or custom implementation
2. Implement comprehensive test suite
3. Optimize database queries for performance
4. Enhance mobile API features
5. Improve payment gateway integrations

---

## Development Journal

*This section tracks the project's evolution, focusing on major changes, fixes, and decisions.*

### 2025‑04‑18 (Session 3 - Current)

**Focus:** Repository Pattern Refactoring & IDE Cleanup

**Context:** Following successful database seeding, the next step is ensuring the data access layer (Repositories) functions correctly after the InfyOm removal and Laravel 12 upgrade.

**Progress & Accomplishments:**
- **Repository Review:** Began reviewing the custom `app/Repositories/BaseRepository.php` and its concrete implementations (e.g., `UserRepository`, `MarketRepository`). The goal is to ensure they align with Laravel 12 practices and correctly replace the old InfyOm functionality.
- **IDE Diagnostics:** Addressed static analysis warnings reported by the IDE (Intelephense/PHPStan):
    - Added missing Facade imports (`use Illuminate\Support\Facades\DB;`, `use Illuminate\Support\Facades\Log;`) to relevant seeder files (`DriverMarketsTableSeeder`, `MarketsTableSeeder`, `SlidesSeeder`) to resolve "Undefined type" errors.
    - Updated this README's "Troubleshooting" section with guidance on resolving common IDE warnings (facade imports, vendor exclusion, PHP version settings).

**Key Files Touched:**
- `app/Repositories/BaseRepository.php` (Review)
- `database/seeders/DriverMarketsTableSeeder.php` (Added `use DB;`)
- `database/seeders/MarketsTableSeeder.php` (Added `use DB;`)
- `database/seeders/SlidesSeeder.php` (Added `use DB;`, `use Log;`)
- `README.md` (Updated Troubleshooting, expanded Journal)

**Next Steps:**
1.  Continue refactoring repositories: Ensure all repository classes extend the custom `BaseRepository` and correctly implement `model()` and `getFieldsSearchable()`.
2.  Verify repository usage within Controllers and Services to ensure correct data retrieval and manipulation.
3.  Systematically search for and remove any remaining direct or indirect references to the old `InfyOm` packages.
4.  Execute tests (`php artisan test`) to catch regressions introduced during refactoring.

---

### 2025‑04‑18 (Session 2)

**Focus:** Database Seeding Issues

**Context:** After the initial migration and dependency updates, running `php artisan migrate --seed` failed due to foreign key constraint violations.

**Progress & Accomplishments:**
- **Database Seeding Fixed:** Resolved multiple `SQLSTATE[23000]: Integrity constraint violation: 1452` errors during seeding.
    - **`MarketsTableSeeder`:** Modified to use a static array to insert markets with specific IDs (1-10), replacing the `Market::factory()` approach. This ensures predictable IDs for dependent seeders.
    - **`DatabaseSeeder`:** Reordered seeder calls to respect dependencies (e.g., `FieldsTableSeeder` before `MarketsTableSeeder`, which is before `MarketFieldsTableSeeder` and `DriverMarketsTableSeeder`).
    - **`SlidesSeeder`:** Refactored permission seeding. It now inserts permissions using `DB::table('permissions')->insertGetId()`, captures the *actual* returned IDs, and uses these IDs when seeding the `role_has_permissions` pivot table.
- **Error Handling:** Added basic `try/catch` blocks and `Log::error()` calls within problematic seeders to aid future debugging.

**Commands Executed:**
```bash
# Diagnosis & Fix Iteration
php artisan migrate:fresh --seed # Initial failure
php artisan db:seed --class=Database\\Seeders\\MarketsTableSeeder # Test individual seeders
php artisan db:seed --class=Database\\Seeders\\DriverMarketsTableSeeder
php artisan db:seed --class=Database\\Seeders\\MarketFieldsTableSeeder
php artisan db:seed --class=Database\\Seeders\\SlidesSeeder
php artisan tinker # (Used DB::table(...)->pluck('id'); to check data)
php artisan migrate:fresh --seed # Final success
```
- **Database Structure:** Verified the database structure using `php artisan migrate:status` and `php artisan db:wipe` to ensure a clean slate before re-seeding.
- **Database Dump:** Created a fresh database dump using `mysqldump` for backup and future reference.
```bash




## Testing & Validation
### Run all tests
php artisan test

# Run specific test file
php artisan test --filter=ProductAPITest

# Run tests with coverage report (requires Xdebug)
XDEBUG_MODE=coverage php artisan test --coverage

# Run only unit tests
php artisan test --testsuite=Unit

# Run only feature tests
php artisan test --testsuite=Feature


