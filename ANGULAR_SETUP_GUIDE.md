# Angular Frontend Setup Guide

## Project Setup Complete ✅

The Laravel backend has been cleaned up and configured for API-only operations. Now let's set up the Angular frontend.

---

## Step 1: Create Angular Project (Outside desisabji-12 folder)

```bash
# Navigate to parent directory
cd /Users/tourist/code

# Create new Angular project
ng new desisabji-frontend --routing --style=scss

# Navigate to the project
cd desisabji-frontend
```

Or if you want to use the exact project structure, run:

```bash
npm init -y
ng new . --routing --style=scss --package-manager=npm
```

---

## Step 2: Install Additional Dependencies

```bash
cd desisabji-frontend

# Core dependencies
npm install @angular/common @angular/core @angular/forms @angular/router

# HTTP and API communication
npm install @angular/common/http

# State management (optional but recommended)
npm install @ngrx/store @ngrx/effects

# UI Framework (Bootstrap)
npm install bootstrap @popperjs/core

# HTTP interceptor for auth tokens
npm install axios

# Dev dependencies
npm install -D @angular/cli typescript
```

---

## Step 3: Project Structure

```
desisabji-frontend/
├── src/
│   ├── app/
│   │   ├── core/                      # Core module
│   │   │   ├── guards/
│   │   │   │   └── auth.guard.ts
│   │   │   ├── interceptors/
│   │   │   │   └── auth.interceptor.ts
│   │   │   ├── services/
│   │   │   │   ├── api.service.ts
│   │   │   │   ├── auth.service.ts
│   │   │   │   ├── product.service.ts
│   │   │   │   ├── cart.service.ts
│   │   │   │   └── order.service.ts
│   │   │   └── core.module.ts
│   │   │
│   │   ├── shared/                    # Shared module
│   │   │   ├── components/
│   │   │   │   ├── navbar/
│   │   │   │   ├── footer/
│   │   │   │   └── spinner/
│   │   │   ├── models/
│   │   │   │   ├── user.model.ts
│   │   │   │   ├── product.model.ts
│   │   │   │   ├── cart.model.ts
│   │   │   │   └── order.model.ts
│   │   │   └── shared.module.ts
│   │   │
│   │   ├── features/                  # Feature modules
│   │   │   ├── auth/
│   │   │   │   ├── login/
│   │   │   │   ├── register/
│   │   │   │   └── auth.module.ts
│   │   │   ├── products/
│   │   │   │   ├── product-list/
│   │   │   │   ├── product-detail/
│   │   │   │   └── products.module.ts
│   │   │   ├── cart/
│   │   │   │   ├── cart-view/
│   │   │   │   └── cart.module.ts
│   │   │   ├── checkout/
│   │   │   │   ├── checkout-view/
│   │   │   │   └── checkout.module.ts
│   │   │   └── orders/
│   │   │       ├── order-list/
│   │   │       ├── order-detail/
│   │   │       └── orders.module.ts
│   │   │
│   │   ├── app.component.ts
│   │   ├── app.component.html
│   │   ├── app.component.scss
│   │   ├── app.routes.ts              # Routing configuration
│   │   └── app.module.ts              # or use standalone
│   │
│   ├── assets/                        # Images, fonts, etc.
│   │   ├── images/
│   │   ├── fonts/
│   │   └── icons/
│   │
│   ├── styles/                        # Global styles
│   │   ├── variables.scss
│   │   ├── mixins.scss
│   │   ├── globals.scss
│   │   └── styles.scss
│   │
│   ├── environments/
│   │   ├── environment.ts             # Dev
│   │   └── environment.prod.ts        # Production
│   │
│   ├── main.ts
│   └── index.html
│
├── angular.json
├── tsconfig.json
├── tsconfig.app.json
├── tsconfig.spec.json
├── package.json
├── .env
├── .env.example
└── README.md
```

---

## Step 4: Environment Configuration

Create `src/environments/environment.ts`:

```typescript
export const environment = {
  production: false,
  apiUrl: 'http://localhost:8000/api',
  apiVersion: 'v1',
};
```

Create `src/environments/environment.prod.ts`:

```typescript
export const environment = {
  production: true,
  apiUrl: 'https://api.yourdomain.com/api',
  apiVersion: 'v1',
};
```

Create `.env`:

```env
NG_APP_API_URL=http://localhost:8000/api
NG_APP_API_VERSION=v1
```

---

## Step 5: API Service Setup

Create `src/app/core/services/api.service.ts`:

```typescript
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError, retry } from 'rxjs/operators';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class ApiService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  /**
   * Get request
   */
  get<T>(endpoint: string, params?: any): Observable<T> {
    let httpParams = new HttpParams();
    
    if (params) {
      Object.keys(params).forEach(key => {
        httpParams = httpParams.set(key, params[key]);
      });
    }

    return this.http.get<T>(`${this.apiUrl}${endpoint}`, { params: httpParams })
      .pipe(
        retry(1),
        catchError(this.handleError)
      );
  }

  /**
   * Post request
   */
  post<T>(endpoint: string, data: any): Observable<T> {
    return this.http.post<T>(`${this.apiUrl}${endpoint}`, data)
      .pipe(
        catchError(this.handleError)
      );
  }

  /**
   * Put request
   */
  put<T>(endpoint: string, data: any): Observable<T> {
    return this.http.put<T>(`${this.apiUrl}${endpoint}`, data)
      .pipe(
        catchError(this.handleError)
      );
  }

  /**
   * Delete request
   */
  delete<T>(endpoint: string): Observable<T> {
    return this.http.delete<T>(`${this.apiUrl}${endpoint}`)
      .pipe(
        catchError(this.handleError)
      );
  }

  /**
   * Handle errors
   */
  private handleError(error: any) {
    let errorMessage = 'An error occurred';
    
    if (error.error instanceof ErrorEvent) {
      // Client-side error
      errorMessage = `Error: ${error.error.message}`;
    } else {
      // Server-side error
      errorMessage = `Error Code: ${error.status}\nMessage: ${error.message}`;
    }
    
    console.error(errorMessage);
    return throwError(() => new Error(errorMessage));
  }
}
```

---

## Step 6: Auth Service

Create `src/app/core/services/auth.service.ts`:

```typescript
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, BehaviorSubject } from 'rxjs';
import { map, tap } from 'rxjs/operators';
import { ApiService } from './api.service';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private currentUserSubject: BehaviorSubject<any>;
  public currentUser: Observable<any>;
  
  private tokenKey = 'auth_token';
  private userKey = 'current_user';

  constructor(
    private http: HttpClient,
    private api: ApiService
  ) {
    this.currentUserSubject = new BehaviorSubject<any>(
      JSON.parse(localStorage.getItem(this.userKey) || '{}')
    );
    this.currentUser = this.currentUserSubject.asObservable();
  }

  /**
   * Get current user value
   */
  public get currentUserValue(): any {
    return this.currentUserSubject.value;
  }

  /**
   * Get auth token
   */
  public getToken(): string | null {
    return localStorage.getItem(this.tokenKey);
  }

  /**
   * Check if user is authenticated
   */
  public isAuthenticated(): boolean {
    return !!this.getToken();
  }

  /**
   * Login
   */
  login(email: string, password: string): Observable<any> {
    return this.api.post('/login', { email, password })
      .pipe(
        tap(response => {
          if (response && response.data && response.data.token) {
            localStorage.setItem(this.tokenKey, response.data.token);
            localStorage.setItem(this.userKey, JSON.stringify(response.data.user));
            this.currentUserSubject.next(response.data.user);
          }
        })
      );
  }

  /**
   * Register
   */
  register(data: any): Observable<any> {
    return this.api.post('/signup', data)
      .pipe(
        tap(response => {
          if (response && response.data && response.data.token) {
            localStorage.setItem(this.tokenKey, response.data.token);
            localStorage.setItem(this.userKey, JSON.stringify(response.data.user));
            this.currentUserSubject.next(response.data.user);
          }
        })
      );
  }

  /**
   * Logout
   */
  logout(): Observable<any> {
    return this.api.get('/logout')
      .pipe(
        tap(() => {
          localStorage.removeItem(this.tokenKey);
          localStorage.removeItem(this.userKey);
          this.currentUserSubject.next({});
        })
      );
  }

  /**
   * Get current user
   */
  getCurrentUser(): Observable<any> {
    return this.api.get('/user');
  }
}
```

---

## Step 7: Auth Interceptor

Create `src/app/core/interceptors/auth.interceptor.ts`:

```typescript
import { Injectable } from '@angular/core';
import {
  HttpRequest,
  HttpHandler,
  HttpEvent,
  HttpInterceptor,
  HttpErrorResponse
} from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { AuthService } from '../services/auth.service';

@Injectable()
export class AuthInterceptor implements HttpInterceptor {
  constructor(private authService: AuthService) {}

  intercept(
    request: HttpRequest<unknown>,
    next: HttpHandler
  ): Observable<HttpEvent<unknown>> {
    // Get auth token
    const token = this.authService.getToken();

    // Clone request and add auth header if token exists
    if (token) {
      request = request.clone({
        setHeaders: {
          Authorization: `Bearer ${token}`
        }
      });
    }

    // Add content-type header
    if (!request.headers.has('Content-Type')) {
      request = request.clone({
        setHeaders: {
          'Content-Type': 'application/json'
        }
      });
    }

    return next.handle(request)
      .pipe(
        catchError((error: HttpErrorResponse) => {
          if (error.status === 401) {
            // Unauthorized - log out user
            this.authService.logout().subscribe();
          }
          return throwError(() => error);
        })
      );
  }
}
```

---

## Step 8: Auth Guard

Create `src/app/core/guards/auth.guard.ts`:

```typescript
import { Injectable } from '@angular/core';
import {
  CanActivate,
  ActivatedRouteSnapshot,
  RouterStateSnapshot,
  UrlTree,
  Router
} from '@angular/router';
import { Observable } from 'rxjs';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class AuthGuard implements CanActivate {
  constructor(
    private authService: AuthService,
    private router: Router
  ) {}

  canActivate(
    route: ActivatedRouteSnapshot,
    state: RouterStateSnapshot
  ): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
    
    if (this.authService.isAuthenticated()) {
      return true;
    }

    // Not authenticated - redirect to login
    this.router.navigate(['/auth/login'], {
      queryParams: { returnUrl: state.url }
    });
    return false;
  }
}
```

---

## Step 9: Product Service

Create `src/app/core/services/product.service.ts`:

```typescript
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';

@Injectable({
  providedIn: 'root'
})
export class ProductService {
  constructor(private api: ApiService) {}

  /**
   * Get all products
   */
  getProducts(page: number = 1, limit: number = 15): Observable<any> {
    return this.api.get('/products', { page, limit });
  }

  /**
   * Get products by category
   */
  getProductsByCategory(categoryId: number): Observable<any> {
    return this.api.get(`/products/categories/${categoryId}`);
  }

  /**
   * Get product by ID
   */
  getProduct(id: number): Observable<any> {
    return this.api.get(`/products/${id}`);
  }

  /**
   * Search products
   */
  searchProducts(query: string): Observable<any> {
    return this.api.get('/product/search', { q: query });
  }

  /**
   * Get categories
   */
  getCategories(): Observable<any> {
    return this.api.get('/products/categories');
  }
}
```

---

## Step 10: Cart Service

Create `src/app/core/services/cart.service.ts`:

```typescript
import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable } from 'rxjs';
import { ApiService } from './api.service';
import { tap } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class CartService {
  private cartSubject = new BehaviorSubject<any>([]);
  public cart$ = this.cartSubject.asObservable();

  constructor(private api: ApiService) {
    this.loadCart();
  }

  /**
   * Load cart from backend
   */
  loadCart(): void {
    this.api.get('/carts')
      .pipe(
        tap(response => {
          this.cartSubject.next(response.data || []);
        })
      )
      .subscribe();
  }

  /**
   * Add item to cart
   */
  addToCart(productId: number, quantity: number = 1): Observable<any> {
    return this.api.post('/carts', {
      product_id: productId,
      quantity
    }).pipe(
      tap(() => this.loadCart())
    );
  }

  /**
   * Update cart item
   */
  updateCart(itemId: number, quantity: number): Observable<any> {
    return this.api.put(`/carts/${itemId}`, { quantity })
      .pipe(
        tap(() => this.loadCart())
      );
  }

  /**
   * Remove from cart
   */
  removeFromCart(itemId: number): Observable<any> {
    return this.api.delete(`/carts/${itemId}`)
      .pipe(
        tap(() => this.loadCart())
      );
  }

  /**
   * Get cart count
   */
  getCartCount(): Observable<any> {
    return this.api.get('/carts/count');
  }

  /**
   * Clear cart
   */
  clearCart(): Observable<any> {
    return this.api.post('/carts/reset', {})
      .pipe(
        tap(() => this.cartSubject.next([]))
      );
  }
}
```

---

## Step 11: App Module Setup

Create `src/app/app.module.ts`:

```typescript
import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { CommonModule } from '@angular/common';

import { AppComponent } from './app.component';
import { AppRoutingModule } from './app.routing';
import { AuthInterceptor } from './core/interceptors/auth.interceptor';

@NgModule({
  declarations: [
    AppComponent
  ],
  imports: [
    BrowserModule,
    CommonModule,
    HttpClientModule,
    AppRoutingModule
  ],
  providers: [
    {
      provide: HTTP_INTERCEPTORS,
      useClass: AuthInterceptor,
      multi: true
    }
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
```

---

## Step 12: Routing Setup

Create `src/app/app.routing.ts`:

```typescript
import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from './core/guards/auth.guard';

const routes: Routes = [
  {
    path: 'auth',
    loadChildren: () => import('./features/auth/auth.module').then(m => m.AuthModule)
  },
  {
    path: 'products',
    loadChildren: () => import('./features/products/products.module').then(m => m.ProductsModule)
  },
  {
    path: 'cart',
    loadChildren: () => import('./features/cart/cart.module').then(m => m.CartModule),
    canActivate: [AuthGuard]
  },
  {
    path: 'checkout',
    loadChildren: () => import('./features/checkout/checkout.module').then(m => m.CheckoutModule),
    canActivate: [AuthGuard]
  },
  {
    path: 'orders',
    loadChildren: () => import('./features/orders/orders.module').then(m => m.OrdersModule),
    canActivate: [AuthGuard]
  },
  {
    path: '',
    redirectTo: '/products',
    pathMatch: 'full'
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
```

---

## Step 13: Running Development Servers

### Terminal 1: Start Laravel Backend API

```bash
cd /Users/tourist/code/desisabji-12
php artisan serve
# Runs on http://localhost:8000
```

### Terminal 2: Start Angular Frontend

```bash
cd /Users/tourist/code/desisabji-frontend
ng serve
# Runs on http://localhost:4200
```

### Access the Application

- **Frontend:** http://localhost:4200
- **API:** http://localhost:8000/api
- **API Status:** http://localhost:8000

---

## Step 14: Building for Production

### Build Angular

```bash
cd desisabji-frontend
ng build --configuration production
# Output in dist/desisabji-frontend/
```

### Deploy Frontend

**Option 1: Vercel (Recommended)**

```bash
npm install -g vercel
vercel --prod --dir=dist/desisabji-frontend
```

**Option 2: Netlify**

```bash
npm install -g netlify-cli
netlify deploy --prod --dir=dist/desisabji-frontend
```

**Option 3: AWS S3 + CloudFront**

```bash
aws s3 cp dist/desisabji-frontend s3://your-bucket-name --recursive
```

### Deploy Laravel Backend

```bash
# Push to Heroku
git push heroku master

# Or use Docker
docker build -t desisabji-backend .
docker push your-registry/desisabji-backend
```

---

## Important Notes

1. **CORS Configuration**: Already set in Laravel for `http://localhost:4200`
2. **Authentication**: Uses Laravel Sanctum tokens
3. **API Response Format**: All responses follow standard JSON format
4. **Error Handling**: Implement global error handler in Angular
5. **Storage**: Tokens stored in localStorage (consider sessionStorage for security)

---

## Troubleshooting

### CORS Errors

If you see CORS errors, ensure:
1. Laravel CORS config includes your Angular domain
2. Backend is running on http://localhost:8000
3. Angular is running on http://localhost:4200

### Authentication Failures

1. Check token is being sent in Authorization header
2. Verify backend token validation
3. Check token expiry time

### API Not Responding

1. Ensure Laravel server is running
2. Check API endpoint URL in environment.ts
3. Verify network tab in browser DevTools

---

## Next Steps

1. Create authentication pages (login, register)
2. Create product listing pages
3. Implement cart functionality
4. Build checkout page
5. Integrate payment gateways
6. Add admin dashboard
7. Write tests

---

**Good luck! 🚀**
