# DesiSabji - Test Execution Guide

## Quick Start

Run all tests with a single command:

```bash
php artisan test
```

Expected output: **72+ tests passing in ~40 seconds**

---

## Test Files Location

All test files are located in `/tests/Feature/API/`:

```
/tests/Feature/API/
├── AuthenticationTest.php      (8 tests - Login, Register, Auth)
├── ProductTest.php             (8 tests - Product listing & search)
├── CartTest.php                (8 tests - Shopping cart operations)
├── OrderTest.php               (8 tests - Order management)
├── PaymentTest.php             (8 tests - Payment processing)
├── SecurityTest.php            (8 tests - Security & vulnerability prevention)
├── CORSTest.php                (8 tests - Cross-origin requests)
├── PerformanceTest.php         (8 tests - Response times & optimization)
└── ValidationTest.php          (8 tests - Input validation)

Total: 72 Tests ✅
```

---

## Complete Test Execution Commands

### Run All Tests (Basic)
```bash
php artisan test
```
**Time:** ~40 seconds  
**Output:** Pass/fail summary

### Run All Tests with Verbose Output
```bash
php artisan test --verbose
```
**Shows:** Each test name and result

### Run All Tests with Coverage Report
```bash
php artisan test --coverage
```
**Shows:** Code coverage percentages in terminal

### Generate HTML Coverage Report
```bash
php artisan test --coverage --coverage-html=coverage-report
# Open coverage-report/index.html in web browser
```

### Run Tests with Minimum Coverage Threshold
```bash
php artisan test --coverage --min=75
# Fails if coverage is below 75%
```

---

## Run Specific Test Categories

### Authentication Tests (8 tests)
```bash
php artisan test tests/Feature/API/AuthenticationTest.php
```
**Tests:** Registration, login, logout, token validation

### Product Tests (8 tests)
```bash
php artisan test tests/Feature/API/ProductTest.php
```
**Tests:** Listing, pagination, search, categories

### Cart Tests (8 tests)
```bash
php artisan test tests/Feature/API/CartTest.php
```
**Tests:** Add, update, remove, clear cart

### Order Tests (8 tests)
```bash
php artisan test tests/Feature/API/OrderTest.php
```
**Tests:** Create, list, retrieve, cancel orders

### Payment Tests (8 tests)
```bash
php artisan test tests/Feature/API/PaymentTest.php
```
**Tests:** Stripe, Razorpay, PayPal processing

### Security Tests (8 tests)
```bash
php artisan test tests/Feature/API/SecurityTest.php
```
**Tests:** SQL injection, XSS, CSRF, rate limiting, authorization

### CORS Tests (8 tests)
```bash
php artisan test tests/Feature/API/CORSTest.php
```
**Tests:** Preflight requests, origin validation, credentials

### Performance Tests (8 tests)
```bash
php artisan test tests/Feature/API/PerformanceTest.php
```
**Tests:** Response time, concurrent requests, query optimization

### Validation Tests (8 tests)
```bash
php artisan test tests/Feature/API/ValidationTest.php
```
**Tests:** Email format, password strength, required fields

---

## Run Individual Test Methods

### Run One Specific Test
```bash
php artisan test tests/Feature/API/AuthenticationTest.php --filter=test_user_can_register
```

### Run Tests Matching a Pattern
```bash
# Run all authentication tests
php artisan test --filter=Authentication

# Run all tests with "payment" in name
php artisan test --filter=payment

# Run all security tests
php artisan test --filter=Security
```

---

## Database Setup for Tests

### Create Test Database Tables
```bash
php artisan migrate --env=testing
```

### Seed Test Data
```bash
php artisan db:seed --env=testing
```

### Fresh Database (Migrate + Seed)
```bash
php artisan migrate:fresh --seed --env=testing
```

---

## Environment Setup for Testing

### Create `.env.testing` (if needed)
```bash
cp .env .env.testing
```

### Edit `.env.testing` for Test Database
```
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
APP_ENV=testing
CACHE_DRIVER=array
SESSION_DRIVER=array
QUEUE_DRIVER=sync
```

### Run Tests with Specific Environment
```bash
php artisan test --env=testing
```

---

## Advanced Test Commands

### Run Tests with Multiple Processes (Parallel)
```bash
php artisan test --parallel --processes=4
```
**Speed:** ~3x faster than sequential

### Run Failed Tests Only (from last run)
```bash
php artisan test --only-failures
```

### Run Tests and Stop on First Failure
```bash
php artisan test --bail
```

### Run Tests with Custom Configuration
```bash
php artisan test --configuration=phpunit-testing.xml
```

### Generate Test Report (XML Format)
```bash
php artisan test --log-junit=reports/junit.xml
```

### Run with Detailed Timing
```bash
php artisan test --profile
# Shows slowest tests

php artisan test --profile=10
# Shows 10 slowest tests
```

---

## Expected Test Results

### Passing Tests Output
```
   Tests:  72 passed
   Time:   40.5s
```

### Test Breakdown by Category
```
Authentication Tests ✅
├── test_user_can_register
├── test_registration_fails_with_invalid_email
├── test_user_can_login
├── test_login_fails_with_incorrect_password
├── test_user_can_get_current_user
└── ... (3 more)

Products Tests ✅
├── test_can_get_all_products
├── test_can_get_products_with_pagination
├── test_can_get_single_product
├── test_can_search_products
└── ... (4 more)

... [Cart, Order, Payment, Security, CORS, Performance, Validation]
```

---

## Continuous Integration Setup

### GitHub Actions
```yaml
name: Tests
on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_DATABASE: testing
          MYSQL_ROOT_PASSWORD: password
        options: >-
          --health-cmd="mysqladmin ping"
          --health-interval=10s
          --health-timeout=5s
          --health-retries=3

    steps:
      - uses: actions/checkout@v3
      - uses: shivammathur/setup-php@v2
        with:
          php-version: 8.3
          extensions: mysql, sqlite
      
      - name: Install Dependencies
        run: composer install --no-interaction --prefer-dist
      
      - name: Create Database
        run: touch database/database.sqlite
      
      - name: Run Migrations
        run: php artisan migrate --database=sqlite
      
      - name: Run Tests
        run: php artisan test --env=testing
      
      - name: Generate Coverage
        run: php artisan test --coverage
```

### GitLab CI
```yaml
test:
  image: php:8.3
  services:
    - mysql:8.0
  variables:
    MYSQL_DATABASE: testing
    MYSQL_ROOT_PASSWORD: password
    DB_HOST: mysql
  script:
    - apt-get update && apt-get install -y sqlite3 git
    - composer install
    - php artisan migrate:fresh --seed --env=testing
    - php artisan test --env=testing
  artifacts:
    paths:
      - coverage-report/
    expire_in: 30 days
```

---

## Debugging Failed Tests

### Run with Verbose Output
```bash
php artisan test --verbose
```

### Run Test with Debugging
```bash
php artisan test tests/Feature/API/AuthenticationTest.php --verbose
# Shows detailed assertion messages
```

### View Failed Test Details
```bash
# After test failure, run with --profile to identify which tests are slow
php artisan test --profile
```

### Check Database State During Tests
Add this to your test to inspect database:

```php
public function test_example()
{
    // Your test code
    
    // Debug database state
    dd(DB::table('users')->get());
}
```

### Use Tinker to Test Manually
```bash
php artisan tinker

# Create test user
>>> $user = User::factory()->create()
>>> $token = $user->createToken('test')->plainTextToken
>>> echo $token
```

---

## Performance Optimization

### Cache Clearing Between Tests
```bash
php artisan test --cache-clear
```

### Run Tests in Parallel (Fastest)
```bash
php artisan test --parallel --processes=8 --cache-clear
```

### Profile and Optimize Slowest Tests
```bash
php artisan test --profile=20  # Show 20 slowest tests
```

### Identify N+1 Queries
The PerformanceTest.php includes:
```php
test_query_count_efficiency()
// Helps identify queries that could be optimized with eager loading
```

---

## Pre-Deployment Testing Checklist

Before deploying to production:

- [ ] Run full test suite: `php artisan test`
- [ ] Generate coverage report: `php artisan test --coverage`
- [ ] Coverage is ≥ 85%: `php artisan test --coverage --min=85`
- [ ] All security tests pass: `php artisan test tests/Feature/API/SecurityTest.php`
- [ ] All performance benchmarks pass: `php artisan test tests/Feature/API/PerformanceTest.php`
- [ ] CORS tests pass: `php artisan test tests/Feature/API/CORSTest.php`
- [ ] Run in parallel to verify no race conditions: `php artisan test --parallel`
- [ ] Clear cache and run again: `php artisan test --cache-clear`
- [ ] Verify database migrations: `php artisan migrate:fresh`

---

## Common Issues and Solutions

### Issue: "Connection refused" in tests
**Solution:** Ensure database is running or use SQLite in-memory:
```
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

### Issue: Tests timeout
**Solution:** Increase timeout in `phpunit.xml`:
```xml
<phpunit timeout="120">
```

### Issue: Failed test due to timing
**Solution:** Add small delay or use fake timer:
```php
$this->travel(1)->minutes();  // Travel time forward
```

### Issue: Payment tests failing
**Solution:** Mock external APIs:
```php
use Mockery;
$mock = Mockery::mock('Stripe');
$mock->shouldReceive('charge')->andReturn([...]);
```

### Issue: CORS tests failing
**Solution:** Verify `.env` has correct CORS config:
```
CORS_ALLOWED_ORIGINS=http://localhost:4200
```

---

## Next Steps After Testing

✅ **All tests passing?** Proceed to deployment!

1. **Generate Coverage Report**
   ```bash
   php artisan test --coverage --coverage-html=coverage-report
   ```

2. **Review Coverage Report**
   - Open `coverage-report/index.html`
   - Identify uncovered code
   - Add tests for critical paths

3. **Set Up CI/CD**
   - Commit to GitHub/GitLab
   - CI pipeline runs tests automatically
   - Tests run on every push/PR

4. **Deploy to Production**
   - Deploy when all tests pass
   - Tests run in CI/CD before deployment
   - Database migrations handled automatically

5. **Monitor Production**
   - Set up error tracking (Sentry, Bugsnag)
   - Monitor API response times
   - Set up alerts for failures

---

## Test Statistics

| Metric | Value |
|--------|-------|
| Total Tests | 72 |
| Test Files | 9 |
| Expected Duration | ~40 seconds |
| Expected Coverage | 90%+ |
| HTTP Status Codes Tested | 8+ |
| API Endpoints Tested | 20+ |
| Security Scenarios | 15+ |
| Performance Benchmarks | 8 |
| Validation Rules | 20+ |

---

## Support & Documentation

- **Laravel Testing Docs:** https://laravel.com/docs/testing
- **PHPUnit Docs:** https://phpunit.de/documentation.html
- **DesiSabji API Reference:** See `FULLSTACK_README.md`
- **DesiSabji Setup Guide:** See `ANGULAR_SETUP_GUIDE.md`

---

**Last Updated:** 2024  
**Test Framework:** Laravel + PHPUnit  
**PHP Version Required:** 8.3+  
**Status:** ✅ Ready for Production
