# DesiSabji Laravel Project Documentation

This document outlines the setup, migration, and maintenance of the DesiSabji e-commerce project based on Laravel 12.

## Project Overview

DesiSabji is an e-commerce marketplace application that allows customers to browse products, place orders, and have them delivered. The application includes:

- User management with authentication and roles
- Product management
- Order processing
- Payment gateway integration (PayPal, Stripe, RazorPay)
- Delivery tracking
- Vendor management
- Admin dashboard

## Technical Stack

- **Framework**: Laravel 12 (migrated from Laravel 8)
- **Database**: MySQL/MariaDB
- **Frontend Build Tool**: Vite (migrated from Laravel Mix)
- **Repository Pattern**: Custom implementation (replacing InfyOm)

## Initial Setup

### Prerequisites

- PHP 8.2 or higher
- MariaDB 10.x
- Composer
- Node.js and npm

### Environment Setup

1. Clone the repository:
   ```bash
   git clone [repository-url]
   cd desisabji-12
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install Node.js dependencies:
   ```bash
   npm install
   ```

4. Create environment file:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure database connection in `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=algolive
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

6. Configure MariaDB user permissions:
   ```sql
   SET PASSWORD FOR 'root'@'localhost' = PASSWORD('your_password');
   FLUSH PRIVILEGES;
   CREATE DATABASE algolive;
   ```

7. Run migrations and seed the database:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

8. Link storage for media:
   ```bash
   php artisan storage:link
   ```

9. Compile assets:
   ```bash
   npm run dev
   # or for production
   npm run build
   ```

10. Start the development server:
    ```bash
    php artisan serve
    ```

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

## User Roles

The application has the following user roles:

1. `admin` - Full system access
2. `manager` - Manages markets and products
3. `client` - Regular customer
4. `driver` - Delivery personnel

## Maintenance Commands

- Clear cache:
  ```bash
  php artisan config:clear
  php artisan cache:clear
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

## Environmental Variables

Important environment variables:

- `APP_URL` - Application URL
- `DB_CONNECTION` - Database connection type
- `MAIL_MAILER` - Email service configuration
- `GOOGLE_MAPS_KEY` - For location features
- `STRIPE_KEY` / `STRIPE_SECRET` - Stripe payment gateway
- `PAYPAL_CLIENT_ID` / `PAYPAL_SECRET` - PayPal integration
- `RAZORPAY_KEY` / `RAZORPAY_SECRET` - RazorPay integration

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

## Future Improvements

1. Replace remaining InfyOm dependencies with Backpack or custom implementation
2. Implement comprehensive test suite
3. Optimize database queries for performance
4. Enhance mobile API features
5. Improve payment gateway integrations

## Resources

- [Laravel Documentation](https://laravel.com/docs/12.x)
- [MariaDB Documentation](https://mariadb.com/kb/en/documentation/)
- [Vite Documentation](https://vitejs.dev/guide/)