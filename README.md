# Desisabji - Full Stack E-commerce Platform

A modern **decoupled e-commerce application** with:
- **Backend:** Laravel 12 REST API
- **Frontend:** Angular 18+ SPA
- **Features:** Product management, order processing, payment integration (PayPal, Stripe, Razorpay), user authentication, and real-time updates

## Project Overview

**Desisabji** is a full-featured e-commerce platform built with:
- **PHP 8.3+** and **Laravel 12**
- **Livewire 3** for reactive components
- **DataTables** for data management
- **PayPal & Stripe** payment gateway integration
- **Spatie Permission** for role-based access control
- **Media Library** for file uploads
- **MySQL** database

## Prerequisites

Before setting up this project locally, ensure you have the following installed:

- **PHP 8.3** or higher
- **Composer** (PHP dependency manager)
- **Node.js 16+** and **npm** (for frontend assets)
- **MySQL 8.0+** or **MariaDB**
- **Git**

## Local Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/kj114022/desisabji-12.git
cd desisabji-12
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Setup Environment File

Copy the example environment file and update configuration:

```bash
cp .env.example .env
```

Update the `.env` file with your local configuration:

```env
APP_NAME=Desisabji
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=desisabji_db
DB_USERNAME=root
DB_PASSWORD=your_password

# Mail Configuration (for testing)
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password

# Payment Gateway Keys (Optional - add if needed)
STRIPE_PUBLIC_KEY=your_stripe_key
STRIPE_SECRET_KEY=your_stripe_secret

RAZORPAY_PUBLIC_KEY=your_razorpay_key
RAZORPAY_SECRET_KEY=your_razorpay_secret
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Create Database

Create a new MySQL database for the project:

```bash
mysql -u root -p -e "CREATE DATABASE desisabji_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Or use your preferred MySQL client (MySQL Workbench, phpMyAdmin, etc.).

### 6. Run Database Migrations

```bash
php artisan migrate
```

### 7. (Optional) Seed the Database

To populate the database with sample data:

```bash
php artisan db:seed
```

### 8. Install Node Dependencies

```bash
npm install
```

### 9. Build Frontend Assets

```bash
# Development build
npm run dev

# Or for production
npm run prod

# Or watch for changes during development
npm run watch
```

### 10. Start the Development Server

Open a new terminal window and run:

```bash
php artisan serve
```

The application will be available at **http://localhost:8000**

## Project Structure

```
desisabji-12/
├── app/
│   ├── Console/          # Artisan commands
│   ├── Http/
│   │   ├── Controllers/  # Application controllers
│   │   ├── Middleware/   # HTTP middleware
│   │   └── Requests/     # Form request classes
│   ├── Models/           # Eloquent models
│   ├── Repositories/     # Data access layer
│   ├── DataTables/       # DataTables configurations
│   ├── Livewire/         # Livewire components
│   ├── Mail/             # Mailable classes
│   ├── Notifications/    # Notification classes
│   └── Providers/        # Service providers
├── config/               # Configuration files
├── database/
│   ├── migrations/       # Database migrations
│   ├── factories/        # Model factories for testing
│   └── seeds/            # Database seeders
├── resources/
│   ├── views/            # Blade templates
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   └── images/           # Image assets
├── routes/
│   ├── web.php           # Web routes
│   ├── api.php           # API routes
│   └── auth.php          # Authentication routes
├── storage/              # File storage (logs, uploads, cache)
├── tests/                # Test files
├── vendor/               # Composer dependencies
└── public/               # Public assets

```

## Key Features

### Authentication & Authorization
- User registration and login
- Role-based access control (RBAC) using Spatie Permission
- Admin dashboard access

### Product Management
- Product catalog with categories
- Custom fields for product attributes
- Product images and media management
- Inventory management

### Shopping Cart & Orders
- Shopping cart functionality
- Order management
- Order status tracking
- Customer order history

### Payment Integration
- PayPal payment gateway
- Stripe payment integration
- Razorpay support

### Admin Features
- Dashboard with statistics
- User management
- Product management
- Order management
- Settings management

## Running Tests

```bash
# Run all tests
./vendor/bin/pest

# Run specific test file
./vendor/bin/pest tests/Feature/ExampleTest.php

# Run with coverage
./vendor/bin/pest --coverage
```

## Available Artisan Commands

```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Refresh all migrations
php artisan migrate:refresh

# Seed the database
php artisan db:seed

# Create new migration
php artisan make:migration create_table_name

# Create new model
php artisan make:model ModelName

# Create new controller
php artisan make:controller ControllerName

# Create new middleware
php artisan make:middleware MiddlewareName

# Cache config
php artisan config:cache

# Clear all caches
php artisan cache:clear
```

## Useful Resources

- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [Livewire Documentation](https://livewire.laravel.com)
- [Spatie Permission Documentation](https://spatie.be/docs/laravel-permission)
- [Laravel DataTables Documentation](https://yajrabox.com/docs/laravel-datatables)

## Troubleshooting

### Database Connection Error
- Verify MySQL is running
- Check DB credentials in `.env` file
- Ensure database exists: `mysql -u root -p -e "SHOW DATABASES;"`

### Permission Denied Error on storage/
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

### Composer Dependencies Issue
```bash
composer install --no-interaction
composer dump-autoload
```

### Node Modules Issue
```bash
rm -rf node_modules package-lock.json
npm install
```

## Contributing

1. Create a feature branch (`git checkout -b feature/AmazingFeature`)
2. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
3. Push to the branch (`git push origin feature/AmazingFeature`)
4. Open a Pull Request

## License

This project is open-sourced software licensed under the MIT license.

## Support

For support, email your-email@example.com or create an issue on GitHub.
