<?php
/**
 * File name: api.php
 * Last modified: 2020.10.31 at 12:40:48

 *
 */

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Driver\UserAPIController as DriverUserAPIController;
use App\Http\Controllers\API\Manager\UserAPIController as ManagerUserAPIController;
use App\Http\Controllers\API\Manager\MarketAPIController as ManagerMarketAPIController;
use App\Http\Controllers\API\UserAPIController;
use App\Http\Controllers\API\SMSAPIController;
use App\Http\Controllers\API\FieldAPIController;
use App\Http\Controllers\API\CategoryAPIController;
use App\Http\Controllers\API\MarketAPIController;
use App\Http\Controllers\API\FaqCategoryAPIController;
use App\Http\Controllers\API\ProductAPIController;
use App\Http\Controllers\API\GalleryAPIController;
use App\Http\Controllers\API\ProductReviewAPIController;
use App\Http\Controllers\API\FaqAPIController;
use App\Http\Controllers\API\MarketReviewAPIController;
use App\Http\Controllers\API\CurrencyAPIController;
use App\Http\Controllers\API\SlideAPIController;
use App\Http\Controllers\API\OptionGroupAPIController;
use App\Http\Controllers\API\OptionAPIController;
use App\Http\Controllers\API\DeliveryAddressAPIController;
use App\Http\Controllers\API\OrderAPIController;
use App\Http\Controllers\API\OrderStatusAPIController;
use App\Http\Controllers\API\NotificationAPIController;
use App\Http\Controllers\API\PaymentAPIController;
use App\Http\Controllers\API\FavoriteAPIController;
use App\Http\Controllers\API\CartAPIController;
use App\Http\Controllers\API\DriverAPIController;
use App\Http\Controllers\API\EarningAPIController;
use App\Http\Controllers\API\DriversPayoutAPIController;
use App\Http\Controllers\API\MarketsPayoutAPIController;
use App\Http\Controllers\API\CouponAPIController;
use App\Http\Controllers\API\DashboardAPIController;
use App\Http\Controllers\API\ProductOrderAPIController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SignUpAPIController;
use App\Http\Controllers\API\CmsPageAPIController;

   Route::middleware('api')->group(function () {
        Route::post('/authToken', [AuthController::class, 'authToken'])->name('authToken');
   });
         Route::prefix('driver')->group(function () {
            Route::post('/login', [DriverUserAPIController::class, 'login']);
            Route::post('/register', [DriverUserAPIController::class, 'register']);
            Route::post('/send_reset_link_email', [UserAPIController::class, 'sendResetLinkEmail']);
            Route::get('/user', [DriverUserAPIController::class, 'user']);
            Route::get('/logout', [DriverUserAPIController::class, 'logout']);
            Route::get('/settings', [DriverUserAPIController::class, 'settings']);
        });

        Route::prefix('manager')->group(function () {
            Route::post('/login', [ManagerUserAPIController::class, 'login']);
            Route::post('/register', [ManagerUserAPIController::class, 'register']);
            Route::post('/send_reset_link_email', [UserAPIController::class, 'sendResetLinkEmail']);
            Route::get('/user', [ManagerUserAPIController::class, 'user']);
            Route::get('/logout', [ManagerUserAPIController::class, 'logout']);
            Route::get('/settings', [ManagerUserAPIController::class, 'settings']);
        });

    Route::post('/login', [UserAPIController::class, 'login']);
    Route::post('/signup', [SignUpAPIController::class, 'signup']);

    //legacy
    Route::post('/register', [UserAPIController::class, 'register']);

    Route::post('/send_reset_link_email', [UserAPIController::class, 'sendResetLinkEmail']);
    Route::post('/verify_mobile', [SMSAPIController::class, 'mobileOtpCheck']);
    Route::post('/resend_otp_sms', [SMSAPIController::class, 'resendOTPSms']);
    //legacy
    Route::post('/resend_otp/{userId}/{mobile}', [SMSAPIController::class, 'resendUserOTPSms']);

    Route::get('/user', [UserAPIController::class, 'user']);
    Route::get('/logout', [UserAPIController::class, 'logout']);
    Route::get('/settings', [UserAPIController::class, 'settings']);
    Route::post('/reset_password', [UserAPIController::class, 'changePassword']);
    Route::post('/send_reset_password_phone', [UserAPIController::class, 'resetPasswordByPhone']);
    Route::resource('fields', FieldAPIController::class);
    Route::resource('categories', CategoryAPIController::class);
    Route::resource('markets', MarketAPIController::class);
    Route::get('/cities', [MarketAPIController::class, 'cities']);
    Route::get('/city/markets/{id}', [MarketAPIController::class, 'marketsForCity']);


    Route::resource('/faq_categories', FaqCategoryAPIController::class);
    Route::get('/products/categories', [ProductAPIController::class, 'categories']);
    Route::get('/products/categories/{id}', [ProductAPIController::class, 'productsOfCategory']);
    Route::get('/products/priceForCity', [ProductAPIController::class, 'productPriceForCity']);
    Route::get('/products/{id}', [ProductAPIController::class, 'productById']);
    Route::resource('/products', ProductAPIController::class);
    Route::get('/product/search', [ProductAPIController::class, 'search']);
    Route::get('/products/searchByFields', [ProductAPIController::class, 'searchByFields']);
    Route::get('/products/searchByFields/{id}', [ProductAPIController::class, 'searchByFieldsOfCategory']);
    Route::get('/products/fields', [ProductAPIController::class, 'fields']);
    Route::resource('galleries', GalleryAPIController::class);
    Route::resource('product_reviews', ProductReviewAPIController::class);


    Route::resource('faqs', FaqAPIController::class);
    Route::resource('market_reviews', MarketReviewAPIController::class);
    Route::resource('currencies', CurrencyAPIController::class);
    Route::resource('slides', SlideAPIController::class);
    Route::resource('option_groups', OptionGroupAPIController::class);
    Route::resource('options', OptionAPIController::class);
    Route::get('/user/addresses/{id}', [DeliveryAddressAPIController::class, 'getAddressesByUser']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::group(['middleware' => ['role:driver']], function () {
            Route::prefix('driver')->group(function () {
                Route::resource('orders', OrderAPIController::class);
                Route::resource('notifications', NotificationAPIController::class);
                Route::post('/users/{id}', [UserAPIController::class, 'update']);
                Route::resource('faq_categories', FaqCategoryAPIController::class);
                Route::resource('faqs', FaqAPIController::class);
            });
        });
        Route::group(['middleware' => ['role:manager']], function () {
            Route::prefix('manager')->group(function () {
                Route::post('/users/{id}', [UserAPIController::class, 'update']);
                Route::get('/users/drivers_of_market/{id}', [UserAPIController::class, 'driversOfMarket']);
                Route::get('/dashboard/{id}', [DashboardAPIController::class, 'manager']);
                Route::resource('markets', ManagerMarketAPIController::class);
                Route::resource('notifications', NotificationAPIController::class);
            });
        });
    
        Route::post('/users/{id}', [UserAPIController::class, 'update']);
       // Route::patch('/users/{id}', [UserAPIController::class, 'update']);
        Route::resource('order_statuses', OrderStatusAPIController::class);
        Route::get('/user/payments/byMonth', [PaymentAPIController::class, 'byMonth'])->name('payments.byMonth');
        Route::resource('/user/payments', PaymentAPIController::class);
        Route::get('/favorites/exist', [FavoriteAPIController::class, 'exist']);
        Route::resource('favorites', FavoriteAPIController::class);
        Route::resource('orders', OrderAPIController::class);
        Route::resource('order_entries', ProductOrderAPIController::class);
        Route::resource('notifications', NotificationAPIController::class);

        Route::get('/carts/count', [CartAPIController::class, 'count'])->name('carts.count');
        Route::resource('carts', CartAPIController::class);
        Route::post('/carts/reset', [CartAPIController::class, 'resetCart'])->name('carts.reset');
        Route::resource('/user/deliveryAddresses', DeliveryAddressAPIController::class);
        Route::resource('drivers', DriverAPIController::class);
        Route::resource('earnings', EarningAPIController::class);
        Route::resource('driversPayouts', DriversPayoutAPIController::class);
        Route::resource('marketsPayouts', MarketsPayoutAPIController::class);
        Route::resource('coupons', CouponAPIController::class);
       // Route::post('coupons/redeem', [CouponAPIController::class, 'redeem']);
       Route::resource('cms_pages', CmsPageAPIController::class);

});