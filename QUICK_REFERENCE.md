# Quick Reference Card

## 🚀 Start Development Right Now

### Terminal 1: Start Backend
```bash
cd /Users/tourist/code/desisabji-12
php artisan serve
```
✅ Backend runs on: **http://localhost:8000**
✅ API runs on: **http://localhost:8000/api**

### Terminal 2: Create & Start Frontend
```bash
cd /Users/tourist/code
ng new desisabji-frontend --routing --style=scss
cd desisabji-frontend
npm install
ng serve
```
✅ Frontend runs on: **http://localhost:4200**

---

## 📚 Documentation Files

| File | Purpose | Read Time |
|------|---------|-----------|
| `FULLSTACK_README.md` | Complete project overview | 10 min |
| `ANGULAR_SETUP_GUIDE.md` | Angular setup with code examples | 20 min |
| `SETUP_COMPLETE.md` | What's been done & next steps | 5 min |
| `IMPLEMENTATION_CHECKLIST.md` | Phase-by-phase implementation guide | Reference |
| `PRODUCTION_READINESS_SUMMARY.md` | Critical issues summary | 5 min |
| `PRODUCTION_READINESS_ANALYSIS.md` | Detailed technical analysis | 30 min |

---

## 🔑 Key Services to Implement

### 1. ApiService (Core)
```typescript
// In: src/app/core/services/api.service.ts
get<T>(endpoint: string, params?: any): Observable<T>
post<T>(endpoint: string, data: any): Observable<T>
put<T>(endpoint: string, data: any): Observable<T>
delete<T>(endpoint: string): Observable<T>
```

### 2. AuthService
```typescript
// In: src/app/core/services/auth.service.ts
login(email: string, password: string): Observable<any>
register(data: any): Observable<any>
logout(): Observable<any>
getCurrentUser(): Observable<any>
getToken(): string | null
isAuthenticated(): boolean
```

### 3. ProductService
```typescript
// In: src/app/core/services/product.service.ts
getProducts(page: number, limit: number): Observable<any>
getProduct(id: number): Observable<any>
getCategories(): Observable<any>
searchProducts(query: string): Observable<any>
```

### 4. CartService
```typescript
// In: src/app/core/services/cart.service.ts
addToCart(productId: number, quantity: number): Observable<any>
updateCart(itemId: number, quantity: number): Observable<any>
removeFromCart(itemId: number): Observable<any>
getCartCount(): Observable<any>
clearCart(): Observable<any>
```

### 5. OrderService
```typescript
// In: src/app/core/services/order.service.ts
getOrders(): Observable<any>
getOrder(id: number): Observable<any>
createOrder(data: any): Observable<any>
updateOrder(id: number, data: any): Observable<any>
```

---

## 🛣️ Routes to Create

```typescript
const routes: Routes = [
  { path: 'auth/login', component: LoginComponent },
  { path: 'auth/register', component: RegisterComponent },
  { path: 'products', component: ProductListComponent },
  { path: 'products/:id', component: ProductDetailComponent },
  { path: 'cart', component: CartComponent, canActivate: [AuthGuard] },
  { path: 'checkout', component: CheckoutComponent, canActivate: [AuthGuard] },
  { path: 'orders', component: OrdersListComponent, canActivate: [AuthGuard] },
  { path: 'orders/:id', component: OrderDetailComponent, canActivate: [AuthGuard] },
  { path: 'profile', component: ProfileComponent, canActivate: [AuthGuard] },
  { path: '', redirectTo: '/products', pathMatch: 'full' }
];
```

---

## 📡 API Endpoints (Backend Ready)

### Authentication
```
POST   /api/login
POST   /api/signup
GET    /api/logout (needs token)
GET    /api/user (needs token)
```

### Products
```
GET    /api/products
GET    /api/products/{id}
GET    /api/products/categories
GET    /api/product/search?q=keyword
```

### Cart
```
GET    /api/carts (needs token)
POST   /api/carts (needs token)
PUT    /api/carts/{id} (needs token)
DELETE /api/carts/{id} (needs token)
POST   /api/carts/reset (needs token)
```

### Orders
```
GET    /api/orders (needs token)
POST   /api/orders (needs token)
GET    /api/orders/{id} (needs token)
```

### Payments
```
POST   /api/payments (needs token)
GET    /api/payments/byMonth (needs token)
```

---

## 🔐 Token Management

### Getting Token (Angular)
```typescript
// Login response includes token
login() {
  this.authService.login(email, password).subscribe(response => {
    // response.data.token is stored in localStorage automatically
  });
}
```

### Using Token (Automatic via Interceptor)
```typescript
// All requests automatically include token
// Added by AuthInterceptor
// Header: Authorization: Bearer {token}
```

### Manually Get Token
```typescript
const token = this.authService.getToken();
```

---

## 🎨 Component Structure

### Login Component
```typescript
export class LoginComponent {
  email: string;
  password: string;
  
  login() {
    this.authService.login(this.email, this.password)
      .subscribe(
        success => this.router.navigate(['/products']),
        error => this.showError(error)
      );
  }
}
```

### Product List Component
```typescript
export class ProductListComponent implements OnInit {
  products: any[] = [];
  
  ngOnInit() {
    this.productService.getProducts().subscribe(
      response => this.products = response.data
    );
  }
  
  addToCart(product: any) {
    this.cartService.addToCart(product.id, 1)
      .subscribe(() => this.showSuccess('Added to cart'));
  }
}
```

### Cart Component
```typescript
export class CartComponent implements OnInit {
  cartItems: any[] = [];
  total: number = 0;
  
  ngOnInit() {
    this.cartService.cart$.subscribe(items => {
      this.cartItems = items;
      this.calculateTotal();
    });
  }
  
  checkout() {
    this.router.navigate(['/checkout']);
  }
}
```

---

## ⚙️ Configuration Files Needed

### `src/environments/environment.ts`
```typescript
export const environment = {
  production: false,
  apiUrl: 'http://localhost:8000/api'
};
```

### `src/environments/environment.prod.ts`
```typescript
export const environment = {
  production: true,
  apiUrl: 'https://api.yourdomain.com/api'
};
```

### `src/app/app.module.ts`
```typescript
import { HTTP_INTERCEPTORS } from '@angular/common/http';
import { AuthInterceptor } from './core/interceptors/auth.interceptor';

@NgModule({
  providers: [
    {
      provide: HTTP_INTERCEPTORS,
      useClass: AuthInterceptor,
      multi: true
    }
  ]
})
export class AppModule { }
```

---

## 🧪 Testing API Endpoints

### Using curl
```bash
# Get products
curl http://localhost:8000/api/products

# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@test.com","password":"password"}'

# Add to cart (with token)
curl -X POST http://localhost:8000/api/carts \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"product_id":1,"quantity":2}'
```

### Using Postman
1. Import API endpoints
2. Set environment variable for `token`
3. Set Authorization header: `Bearer {{token}}`
4. Test each endpoint

---

## 🐛 Common Issues & Fixes

### CORS Error
**Problem:** Browser blocks API request
**Fix:** 
1. Ensure backend running on port 8000
2. Check `config/cors.php` includes localhost:4200
3. Restart backend server

### 401 Unauthorized
**Problem:** Token expired or missing
**Fix:**
1. Clear localStorage: `localStorage.clear()`
2. Log in again
3. Check token is being sent in header

### 404 Not Found
**Problem:** Endpoint doesn't exist
**Fix:**
1. Check endpoint path spelling
2. Verify backend is running
3. Check API documentation

### CORS Preflight Error
**Problem:** OPTIONS request fails
**Fix:**
1. This is normal, browser sends OPTIONS first
2. Should automatically follow with actual request
3. If still failing, check CORS headers in backend

---

## 📦 NPM Commands

```bash
# Install packages
npm install

# Start dev server
ng serve

# Build for production
ng build --configuration production

# Run tests
ng test

# Run linter
ng lint

# Update packages
npm update

# Clean install (fresh node_modules)
rm -rf node_modules package-lock.json
npm install
```

---

## 🚢 Deployment Quick Links

### Frontend Deployment (Choose One)

**Vercel (Easiest)**
```bash
npm install -g vercel
ng build --configuration production
vercel --prod --dir=dist/desisabji-frontend
```

**Netlify**
```bash
netlify deploy --prod --dir=dist/desisabji-frontend
```

**AWS S3 + CloudFront**
```bash
aws s3 cp dist/desisabji-frontend s3://bucket-name --recursive
```

### Backend Deployment (Choose One)

**Heroku**
```bash
git push heroku main
heroku run php artisan migrate
```

**DigitalOcean App Platform**
- Connect GitHub repo
- Auto-deploy on push

**AWS EC2**
- Launch instance
- Configure server
- Deploy via SSH/Git

---

## 📞 Need Help?

1. Check documentation files in project root
2. Review code examples in services
3. Check browser console for errors
4. Check Laravel logs: `storage/logs/`
5. Use browser DevTools Network tab

---

## ✅ Checklist to Start

- [ ] Backend running on localhost:8000
- [ ] MySQL database created and migrated
- [ ] Angular project created
- [ ] npm dependencies installed
- [ ] Environment files created
- [ ] AuthInterceptor set up
- [ ] First service test working
- [ ] First API call successful

**You're ready to build! 🚀**

---

Last Updated: November 19, 2025
