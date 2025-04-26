<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\VendorOrderController;
use App\Http\Controllers\API\UserAPIController;
use App\Http\Controllers\API\Driver\UserAPIController AS DriverUserAPIController;
use App\Http\Controllers\API\Manager\UserAPIController AS ManagerUserAPIController;
use App\Http\Controllers\API\FieldAPIController;
use App\Http\Controllers\API\CategoryAPIController;
use App\Http\Controllers\API\MarketAPIController;
use App\Http\Controllers\API\Manager\MarketAPIController AS ManagerMarketAPIController;
use App\Http\Controllers\API\FaqCategoryAPIController;
use App\Http\Controllers\API\ProductAPIController;
use App\Http\Controllers\API\GalleryAPIController;
use App\Http\Controllers\API\ProductReviewAPIController;
use App\Http\Controllers\API\ProductCityAPIController;
use App\Http\Controllers\API\FaqAPIController;
use App\Http\Controllers\API\MarketReviewAPIController;
use App\Http\Controllers\API\CurrencyAPIController;
use App\Http\Controllers\API\SlideAPIController;
use App\Http\Controllers\API\OptionGroupAPIController;
use App\Http\Controllers\API\NotificationAPIController;
use App\Http\Controllers\API\OrderAPIController;
use App\Http\Controllers\API\OrderStatusAPIController;
use App\Http\Controllers\API\DeliveryAddressAPIController;
use App\Http\Controllers\API\PaymentAPIController;
use App\Http\Controllers\API\WalletAPIControllerAPI;
use App\Http\Controllers\API\DashboardAPIController;
use App\Http\Controllers\API\DriverAPIController;
use App\Http\Controllers\API\EarningAPIController;
use App\Http\Controllers\API\DriversPayoutAPIController;
use App\Http\Controllers\API\MarketsPayoutAPIController;
use App\Http\Controllers\API\CouponAPIController;
use App\Http\Controllers\API\FavoriteAPIController;
use App\Http\Controllers\API\CartAPIController;
use App\Http\Controllers\API\OptionAPIController;
use App\Http\Controllers\API\ProductOrderAPIController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('orders', [VendorOrderController::class, 'index']);
Route::get('orders/{id}', [VendorOrderController::class, 'show']);
Route::post('orders', [VendorOrderController::class, 'store']);
Route::put('orders/{id}', [VendorOrderController::class, 'update']);
Route::delete('orders/{id}', [VendorOrderController::class, 'delete']);



Route::prefix('driver')->group(function () {
    Route::post('login',[DriverUserAPIController::class, 'login']);
    Route::post('register', [DriverUserAPIController::class, 'register']);
    Route::post('send_reset_link_email', [DriverUserAPIController::class, 'sendResetLinkEmail']);
    Route::get('user', [DriverUserAPIController::class, 'user']);
    Route::get('logout',[DriverUserAPIController::class, 'logout']);
    Route::get('settings',[DriverUserAPIController::class, 'settings']);
});

Route::prefix('manager')->group(function () {
    Route::post('login', [ManagerUserAPIController::class, 'login']);
    Route::post('register', [ManagerUserAPIController::class, 'register']);
    Route::post('send_reset_link_email', [ManagerUserAPIController::class,'sendResetLinkEmail']);
    Route::get('user', [ManagerUserAPIController::class, 'user']);
    Route::get('logout', [ManagerUserAPIController::class, 'logout']);
    Route::get('settings', [ManagerUserAPIController::class, 'settings']);
});


Route::post('login',  [UserAPIController::class, 'login']);
Route::post('register', [UserAPIController::class, 'register']);
Route::post('send_reset_link_email',  [UserAPIController::class, 'sendResetLinkEmail']);
Route::post('verify_mobile',  [UserAPIController::class, 'validateMobileOtp']);
Route::post('resend_otp/{userId}',  [UserAPIController::class, 'resendUserOTPSms']);
Route::get('user',  [UserAPIController::class, 'user']);
Route::get('logout', [UserAPIController::class,'logout']);
Route::get('settings',  [UserAPIController::class, 'settings']);
Route::post('reset_password',  [UserAPIController::class, 'changePassword']);
Route::post('send_reset_password_phone',  [UserAPIController::class, 'resetPasswordByPhone']);
Route::get('fields', [FieldAPIController::class,'show']);
Route::get('categories', [CategoryAPIController::class,'show']);
Route::get('markets', [MarketAPIController::class,'show']);

Route::get('cities', [MarketAPIController::class, 'cities']);
Route::get('states', [MarketAPIController::class, 'states']);
Route::get('city/areas/{id}', [MarketAPIController::class, 'cityArea']);
Route::get('city/markets/{id}', [MarketAPIController::class, 'marketsForCity']);

Route::get('faq_categories', [FaqCategoryAPIController::class, 'index']);
Route::get('products/categories', [ProductAPIController::class, 'categories']);
Route::get('products', [ProductAPIController::class, 'index']);
Route::get('galleries', [GalleryAPIController::class, 'index']);
Route::get('reviews/{id}', [ProductReviewAPIController::class, 'show']);
Route::get('city/products/categories', [ProductCityAPIController::class, 'categories']);
Route::get('city/products', [ProductCityAPIController::class, 'index']);

Route::get('faqs', [FaqAPIController::class, 'index']);
Route::get('market_reviews', [MarketReviewAPIController::class, 'index']);
Route::get('currencies', [CurrencyAPIController::class, 'index']);
Route::get('slides', [SlideAPIController::class, 'index']);

Route::get('option_groups/{id}', [OptionGroupAPIController::class, 'show']);

Route::get('options', [OptionAPIController::class, 'index']);

Route::get('user/addresses/{id}', [DeliveryAddressAPIController::class, 'getAddressesByUser']);
Route::get('products/feature/{id}/{cityId}', [ProductAPIController::class, 'getFeatureProducts']);

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['middleware' => ['role:driver']], function () {
        Route::prefix('driver')->group(function () {
            Route::get('orders', [OrderAPIController::class, 'index']);
            Route::get('notifications', [NotificationAPIController::class, 'index']);
            Route::post('users/{id}', [UserAPIController::class, 'update']);
            Route::get('faq_categories', [FaqCategoryAPIController::class, 'index']);
            Route::get('faqs', [FaqAPIController::class, 'index']);
        });
    });
    Route::group(['middleware' => ['role:manager']], function () {
        Route::prefix('manager')->group(function () {
            Route::post('users/{id}', [UserAPIController::class, 'update']);
            Route::get('users/drivers_of_market/{id}', [ManagerUserAPIController::class, 'driversOfMarket']);
            Route::get('dashboard/{id}', [DashboardAPIController::class, 'manager']);
            Route::get('markets', [ManagerMarketAPIController::class, 'index']);
            Route::get('notifications', [NotificationAPIController::class, 'index']);
        });
    });
    Route::post('users/{id}', [UserAPIController::class, 'update']);

    Route::get('order_statuses', [OrderStatusAPIController::class, 'index']);

    Route::get('payments/byMonth', [PaymentAPIController::class, 'byMonth'])->name('payments.byMonth');
    Route::get('payments', [PaymentAPIController::class, 'index']);

    Route::get('favorites/exist', [FavoriteAPIController::class, 'exist']);
    Route::get('favorites', [FavoriteAPIController::class, 'index']);

    Route::get('orders', [OrderAPIController::class, 'index']);

    Route::get('product_orders', [ProductOrderAPIController::class, 'index']);

    Route::get('notifications', [NotificationAPIController::class, 'index']);

    Route::get('carts/count', [CartAPIController::class, 'count'])->name('carts.count');
    Route::get('carts', [CartAPIController::class, 'index']);
    Route::post('carts/clean/{id}', [CartAPIController::class, 'cleanCart']);
    Route::get('delivery_addresses', [DeliveryAddressAPIController::class, 'index']);
   
 

    Route::get('drivers', [DriverAPIController::class, 'index']);

    Route::get('earnings', [EarningAPIController::class, 'index']);

    Route::get('driversPayouts', [DriversPayoutAPIController::class, 'index']);

    Route::get('marketsPayouts', [MarketsPayoutAPIController::class, 'index']);

    Route::get('coupons', [CouponAPIController::class, 'index']);

    Route::get('wallet', [WalletAPIControllerAPI::class, 'index']);
});