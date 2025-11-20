<?php
/**
 * DesiSabji - Laravel 12 API Backend
 * Web Routes (API Gateway)
 *
 * All frontend requests go to the Angular app
 * This file serves as an API status endpoint only
 */

use Illuminate\Support\Facades\Route;

// API Status Endpoint
Route::get('/', function () {
    return response()->json([
        'status' => 'ok',
        'app' => 'DesiSabji API',
        'version' => config('app.version'),
        'message' => 'Use /api endpoints for all API requests',
        'frontend' => 'Angular App running on http://localhost:4200',
    ]);
})->name('api.status');

/*Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');*/

Route::middleware(['auth'])->group(function () {
    
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});


//Route::get('login/{service}', 'Auth\LoginController@redirectToProvider');
//Route::get('login/{service}/callback', 'Auth\LoginController@handleProviderCallback');
//Auth::routes();
    Route::get('/payments/failed', [PayPalController::class, 'index'])->name('payments.failed');
    Route::get('/payments/razorpay/checkout', [RazorPayController::class, 'checkout']);
    Route::get('/payments/razorpay/pay-success/{userId}/{deliveryAddressId?}/{couponCode?}', [RazorPayController::class, 'paySuccess']);
    Route::get('/payments/razorpay', [PayPalController::class, 'index']);
    Route::get('/payments/paypal/express-checkout', [PayPalController::class, 'getExpressCheckout'])->name('paypal.express-checkout');
    Route::get('/payments/paypal/express-checkout-success', [PayPalController::class, 'getExpressCheckoutSuccess']);
    Route::get('/payments/paypal', [PayPalController::class, 'index'])->name('paypal.index');
    Route::get('/firebase/sw-js', [AppSettingController::class, 'initFirebase']);
    Route::get('/storage/app/public/{id}/{conversion}/{filename?}', [UploadController::class, 'storage']);

    Route::middleware(['auth', App::class])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/uploads/store', [UploadController::class, 'store'])->name('medias.create');
        Route::get('/users/profile', [UserController::class, 'profile'])->name('users.profile');
        Route::post('/users/remove-media', [UserController::class, 'removeMedia']);
        Route::resource('users', UserController::class);
    });
    Route::group(['middleware' => ['permission:medias']], function () {
        Route::get('/uploads/all/{collection?}', [UploadController::class, 'all']);
        Route::get('/uploads/collectionsNames', [UploadController::class, 'collectionsNames']);
        Route::post('/uploads/clear', [UploadController::class, 'clear'])->name('medias.delete');
        Route::get('/medias', [UploadController::class, 'index'])->name('medias');
        Route::get('/uploads/clear-all', [UploadController::class, 'clearAll']);
    });

    Route::group(['middleware' => ['permission:permissions.index']], function () {
        Route::get('/permissions/role-has-permission', [PermissionController::class, 'roleHasPermission']);
        Route::get('/permissions/refresh-permissions', [PermissionController::class, 'refreshPermissions']);;
    });
    Route::group(['middleware' => ['permission:permissions.index']], function () {
        Route::post('/permissions/give-permission-to-role', [PermissionController::class, 'givePermissionToRole']);
        Route::post('/permissions/revoke-permission-to-role', [PermissionController::class, 'revokePermissionToRole']);
    });

    Route::group(['middleware' => ['permission:app-settings']], function () {
        Route::prefix('settings')->group(function () {
            Route::resource('permissions', PermissionController::class);
            Route::resource('roles', RoleController::class);
            Route::resource('customFields', CustomFieldController::class);
            Route::resource('currencies', CurrencyController::class)->except([
                'show'
            ]);
            Route::get('/users/login-as-user/{id}', [UserController::class, 'loginAsUser'])->name('users.login-as-user');
            Route::patch('/update', [AppSettingController::class, 'update']);
            Route::patch('/translate', [AppSettingController::class, 'translate']);
            Route::get('sync-translation', [AppSettingController::class, 'syncTranslation']);
            Route::get('clear-cache', [AppSettingController::class, 'clearCache']);
            Route::get('check-update', [AppSettingController::class, 'checkForUpdates']);
            // disable special character and number in route params
            Route::get('/{type?}/{tab?}', [AppSettingController::class, 'index'])
                ->where('type', '[A-Za-z]*')->where('tab', '[A-Za-z]*')->name('app-settings');
        });
    });

    Route::post('fields/remove-media', [FieldController::class, 'removeMedia']);
    Route::resource('fields', FieldController::class)->except([
        'show'
    ]);

    Route::post('/markets/remove-media', [MarketController::class, 'removeMedia']);
    Route::get('/requestedMarkets', [MarketController::class, 'requestedMarkets'])->name('requestedMarkets.index'); //adeed
    Route::resource('markets', MarketController::class)->except([
        'show'
    ]);

    Route::post('categories/remove-media', [CategoryController::class, 'removeMedia']);
    Route::resource('categories', CategoryController::class)->except([
        'show'
    ]);

    Route::resource('faqCategories', FaqCategoryController::class)->except([
        'show'
    ]);

    Route::resource('orderStatuses', OrderStatusController::class)->except([
        'create', 'store', 'destroy'
    ]);

    Route::post('products/remove-media', [ProductController::class,'removeMedia']);
    Route::resource('products', ProductController::class)->except([
        'show'
    ]);

    Route::post('galleries/remove-media', [GalleryController::class, 'removeMedia']);
    Route::resource('galleries', GalleryController::class)->except([
        'show'
    ]);

    Route::resource('productReviews', ProductReviewController::class)->except([
        'show'
    ]);

    Route::post('options/remove-media', [OptionController::class, 'removeMedia']);


    Route::resource('payments', PaymentController::class)->except([
        'create', 'store','edit', 'destroy'
    ]);

    Route::resource('faqs', FaqController::class)->except([
        'show'
    ]);
    Route::resource('marketReviews', MarketReviewController::class)->except([
        'show'
    ]);

    Route::resource('favorites', FavoriteController::class)->except([
        'show'
    ]);

    Route::resource('orders', OrderController::class);

    Route::resource('notifications', NotificationController::class)->except([
        'create', 'store', 'update','edit',
    ]);

    Route::resource('carts', CartController::class)->except([
        'show','store','create'
    ]);
    Route::resource('deliveryAddresses', DeliveryAddressController::class)->except([
        'show'
    ]);

    Route::resource('drivers', DriverController::class)->except([
        'show'
    ]);

    Route::resource('earnings', EarningController::class)->except([
        'show','edit','update'
    ]);

    Route::resource('driversPayouts', DriversPayoutController::class)->except([
        'show','edit','update'
    ]);

    Route::resource('marketsPayouts', MarketsPayoutController::class)->except([
        'show','edit','update'
    ]);

    Route::resource('optionGroups', OptionGroupController::class)->except([
        'show'
    ]);

    Route::post('options/remove-media',[OptionController::class,'removeMedia']);

    Route::resource('options', OptionController::class)->except([
        'show'
    ]);
    Route::resource('coupons', CouponController::class)->except([
        'show'
    ]);
    Route::post('slides/remove-media',[SlideController::class,'removeMedia']);
    Route::resource('slides', SlideController::class)->except([
        'show'
    ]);

   Route::get('/home', [HomeController::class, 'index'])->name('home');

require __DIR__.'/auth.php';