# DesiSabji - Full Stack E-commerce Platform

[![Laravel](https://img.shields.io/badge/Laravel-12-red)](https://laravel.com)
[![Angular](https://img.shields.io/badge/Angular-18+-red)](https://angular.io)
[![PHP](https://img.shields.io/badge/PHP-8.3+-blue)](https://www.php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-blue)](https://www.mysql.com)

A modern **decoupled e-commerce platform** for organic vegetables and groceries with separate backend API and frontend SPA.

---

## 🏗️ Architecture

```
┌─────────────────────────────────┐
│    Angular Frontend SPA           │
│    (http://localhost:4200)        │
│  - Product Listing                │
│  - Shopping Cart                  │
│  - Checkout & Payments            │
│  - User Accounts                  │
└──────────────┬────────────────────┘
               │ HTTP/JSON
               │ (RESTful API)
               ▼
┌─────────────────────────────────┐
│   Laravel 12 API Backend         │
│  (http://localhost:8000/api)     │
│  - Authentication (Sanctum)      │
│  - Product Management            │
│  - Order Processing              │
│  - Payment Gateway Integration   │
│  - User Management               │
└─────────────────────────────────┘
```

---

## 🚀 Quick Start

### Prerequisites

- **Node.js** 18+ and npm
- **PHP** 8.3+
- **Composer**
- **MySQL** 8.0+
- **Angular CLI** (`npm install -g @angular/cli`)

### Setup in 3 Steps

**Step 1: Clone & Setup Backend**

```bash
cd /Users/tourist/code/desisabji-12
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Backend runs on: **http://localhost:8000**

**Step 2: Create Frontend**

```bash
cd /Users/tourist/code
ng new desisabji-frontend --routing --style=scss
cd desisabji-frontend
npm install
ng serve
```

Frontend runs on: **http://localhost:4200**

**Step 3: Access the App**

Open browser to: **http://localhost:4200**

---

## 📁 Project Structure

### Backend (Laravel API)

```
desisabji-12/
├── app/
│   ├── Http/Controllers/API/     # API Endpoints
│   ├── Models/                   # Database Models
│   ├── Services/                 # Business Logic
│   └── Repositories/             # Data Access Layer
├── routes/
│   ├── api.php                   # API Routes
│   └── web.php                   # Status Endpoint
├── database/
│   ├── migrations/               # Database Migrations
│   └── seeders/                  # Sample Data
├── config/cors.php               # CORS Configuration
└── artisan
```

### Frontend (Angular SPA)

```
desisabji-frontend/
├── src/
│   ├── app/
│   │   ├── core/                 # Core Services & Guards
│   │   ├── shared/               # Shared Components
│   │   ├── features/             # Feature Modules
│   │   │   ├── auth/
│   │   │   ├── products/
│   │   │   ├── cart/
│   │   │   └── orders/
│   │   ├── app.routes.ts         # Routing
│   │   └── app.component.ts
│   ├── styles/                   # Global Styles
│   ├── assets/                   # Images & Fonts
│   └── main.ts
├── angular.json
└── package.json
```

---

## 🔧 Backend Setup Details

### 1. Environment Configuration

Copy `.env.example` to `.env`:

```env
APP_NAME=Desisabji
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=desisabji_db
DB_USERNAME=root
DB_PASSWORD=your_password

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525

STRIPE_PUBLIC_KEY=pk_test_xxx
STRIPE_SECRET_KEY=sk_test_xxx

RAZORPAY_PUBLIC_KEY=xxx
RAZORPAY_SECRET_KEY=xxx
```

### 2. Database Setup

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE desisabji_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations
php artisan migrate

# Seed sample data
php artisan db:seed
```

### 3. Generate API Documentation

```bash
# Generate Swagger/OpenAPI docs
php artisan scribe:generate

# Docs available at: http://localhost:8000/docs
```

### 4. Start Server

```bash
php artisan serve
# Backend API: http://localhost:8000/api
```

---

## 🎨 Frontend Setup Details

### 1. Install Dependencies

```bash
cd desisabji-frontend
npm install
```

### 2. Environment Configuration

Create `src/environments/environment.ts`:

```typescript
export const environment = {
  production: false,
  apiUrl: 'http://localhost:8000/api',
};
```

### 3. Run Development Server

```bash
ng serve --open
# Frontend: http://localhost:4200
```

### 4. Build for Production

```bash
ng build --configuration production
# Output in: dist/desisabji-frontend/
```

---

## 🔐 Authentication Flow

1. **User Login**
   ```
   POST /api/login
   {
     "email": "user@example.com",
     "password": "password"
   }
   ```
   Response includes auth token

2. **Store Token**
   Angular stores token in localStorage

3. **Send Token with Requests**
   ```
   Authorization: Bearer {token}
   ```

4. **Token Validation**
   Backend validates token with Sanctum middleware

5. **Logout**
   ```
   GET /api/logout
   ```
   Clear token from localStorage

---

## 📦 API Endpoints

### Authentication

```
POST   /api/login               # User login
POST   /api/signup              # User registration
POST   /api/logout              # User logout (auth required)
GET    /api/user                # Get current user (auth required)
```

### Products

```
GET    /api/products            # List all products
GET    /api/products/{id}       # Get product details
GET    /api/products/categories # List categories
GET    /api/product/search      # Search products
```

### Shopping Cart

```
GET    /api/carts               # Get user cart
POST   /api/carts               # Add item to cart
PUT    /api/carts/{id}          # Update cart item
DELETE /api/carts/{id}          # Remove cart item
POST   /api/carts/reset         # Clear cart
```

### Orders

```
GET    /api/orders              # List user orders
POST   /api/orders              # Create order
GET    /api/orders/{id}         # Get order details
```

### Payments

```
POST   /api/payments            # Process payment
GET    /api/payments/byMonth    # Get payment history
POST   /api/payments/webhook    # Webhook endpoint
```

---

## 🛠️ Development Commands

### Backend

```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh database
php artisan migrate:fresh --seed

# Clear cache
php artisan cache:clear

# Create model with migration
php artisan make:model ModelName -m

# Create controller
php artisan make:controller Api/ControllerName

# Run tests
php artisan test
```

### Frontend

```bash
# Generate component
ng generate component features/component-name

# Generate service
ng generate service core/services/service-name

# Run tests
ng test

# Build for production
ng build --configuration production

# Lint code
ng lint
```

---

## 📊 API Response Format

All API responses follow this standard format:

### Success Response (200)

```json
{
  "success": true,
  "status_code": 200,
  "message": "Operation successful",
  "data": {
    "id": 1,
    "name": "Product Name",
    "price": 99.99
  }
}
```

### Pagination Response

```json
{
  "success": true,
  "status_code": 200,
  "data": [...],
  "pagination": {
    "total": 100,
    "per_page": 15,
    "current_page": 1,
    "last_page": 7,
    "from": 1,
    "to": 15
  }
}
```

### Error Response (4xx/5xx)

```json
{
  "success": false,
  "status_code": 400,
  "message": "Validation failed",
  "errors": {
    "email": ["Email is required"]
  }
}
```

---

## 🔒 CORS Configuration

CORS is configured in `config/cors.php` to allow:

- `http://localhost:4200` (Angular dev)
- `http://localhost:3000` (Alternate dev)
- `https://yourdomain.com` (Production)

Update for your production domain before deployment.

---

## 💳 Payment Integration

### Stripe

```typescript
// In checkout component
const paymentData = {
  amount: totalPrice,
  currency: 'usd',
  token: stripeToken // Created on frontend only
};

this.paymentService.processStripePayment(paymentData).subscribe(...);
```

### Razorpay

```typescript
const options = {
  key: 'YOUR_RAZORPAY_KEY',
  amount: totalPrice * 100,
  currency: 'INR',
  order_id: orderId
};

razorpay.open();
```

### PayPal

```typescript
// PayPal button automatically handles flow
```

---

## 🧪 Testing

### Backend (PHPUnit)

```bash
php artisan test
php artisan test --filter=UserTest
php artisan test --coverage
```

### Frontend (Jasmine)

```bash
ng test
ng test --coverage
ng test --watch=false
```

---

## 📱 Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

---

## 🚢 Deployment

### Backend Deployment (Heroku Example)

```bash
git push heroku main
heroku run php artisan migrate
```

### Frontend Deployment (Vercel Example)

```bash
npm install -g vercel
vercel --prod --dir=dist/desisabji-frontend
```

---

## 📚 Documentation

- **PRODUCTION_READINESS_SUMMARY.md** - Critical issues & fixes
- **PRODUCTION_READINESS_ANALYSIS.md** - Detailed technical analysis
- **ANGULAR_SETUP_GUIDE.md** - Angular setup instructions
- **LARAVEL_12_MIGRATION_AUDIT.md** - Laravel 12 migration notes

---

## 🐛 Troubleshooting

### CORS Errors

1. Check backend is running on `http://localhost:8000`
2. Verify CORS config includes Angular domain
3. Check browser console for error details

### Authentication Failures

1. Verify token is stored in localStorage
2. Check Authorization header is sent
3. Verify backend token validation

### Database Errors

1. Check MySQL is running
2. Verify DB credentials in `.env`
3. Run `php artisan migrate:fresh --seed`

### API Not Responding

1. Ensure backend server is running
2. Check API endpoint URL in environment.ts
3. Verify network connectivity

---

## 📞 Support

For issues or questions:
1. Check the documentation files in project root
2. Review API responses for error messages
3. Check browser DevTools console for client-side errors
4. Review Laravel logs in `storage/logs/`

---

## 📄 License

This project is licensed under the MIT License - see LICENSE file for details.

---

## 👨‍💻 Development Team

DesiSabji Development Team 2025

---

## 🎯 Key Features

✅ Decoupled Architecture (API + SPA)
✅ User Authentication (Sanctum)
✅ Product Management
✅ Shopping Cart
✅ Order Processing
✅ Multiple Payment Gateways
✅ Role-Based Access Control
✅ Real-time Notifications
✅ Mobile Responsive
✅ Production Ready

---

**Last Updated:** November 19, 2025
**Version:** 1.0.0
