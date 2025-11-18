<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\CartRepositoryInterface;
use App\Repositories\Eloquent\CartRepository;
use App\Repositories\MarketsCityRepositoryInterface;
use App\Repositories\Eloquent\MarketsCityRepository;
use App\Repositories\MarketsPayoutRepositoryInterface;
use App\Repositories\Eloquent\MarketsPayoutRepository;
use App\Repositories\NotificationRepositoryInterface;
use App\Repositories\Eloquent\NotificationRepository;
use App\Repositories\OptionGroupRepositoryInterface;
use App\Repositories\Eloquent\OptionGroupRepository;
use App\Repositories\OptionRepositoryInterface;
use App\Repositories\Eloquent\OptionRepository;  
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\ProductReviewRepositoryInterface;
use App\Repositories\Eloquent\ProductReviewRepository;
use App\Repositories\ProductsCityRepositoryInterface;
use App\Repositories\Eloquent\ProductsCityRepository;
use App\Repositories\CityRepositoryInterface;
use App\Repositories\Eloquent\CityRepository;
use App\Repositories\ProductsOptionRepositoryInterface;
use App\Repositories\Eloquent\ProductsOptionRepository;
use App\Repositories\CouponRepositoryInterface;
use App\Repositories\Eloquent\CouponRepository;
use App\Repositories\CurrencyRepositoryInterface;
use App\Repositories\Eloquent\CurrencyRepository;
use App\Repositories\CustomFieldRepositoryInterface;
use App\Repositories\Eloquent\CustomFieldRepository;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\OrderStatusRepositoryInterface;
use App\Repositories\Eloquent\OrderStatusRepository;
use App\Repositories\PaymentRepositoryInterface;
use App\Repositories\Eloquent\PaymentRepository;
use App\Repositories\PermissionRepositoryInterface;
use App\Repositories\Eloquent\PermissionRepository;
use App\Repositories\MarketRepositoryInterface;
use App\Repositories\Eloquent\MarketRepository;
use App\Repositories\ProductOrderRepositoryInterface;
use App\Repositories\Eloquent\ProductOrderRepository;
use App\Repositories\RoleRepositoryInterface;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\SlideRepositoryInterface;
use App\Repositories\Eloquent\SlideRepository;
use App\Repositories\StateRepositoryInterface;
use App\Repositories\Eloquent\StateRepository;
use App\Repositories\UploadRepositoryInterface;
use App\Repositories\Eloquent\UploadRepository;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\CustomFieldValueRepositoryInterface;
use App\Repositories\Eloquent\CustomFieldValueRepository;
use App\Repositories\DeliveryAddressRepositoryInterface;
use App\Repositories\Eloquent\DeliveryAddressRepository;
use App\Repositories\DiscountableRepositoryInterface;
use App\Repositories\Eloquent\DiscountableRepository;
use App\Repositories\DriverRepositoryInterface;
use App\Repositories\Eloquent\DriverRepository;
use App\Repositories\DriverPayoutRepositoryInterface;
use App\Repositories\Eloquent\DriverPayoutRepository;
use App\Repositories\EarningRepositoryInterface;
use App\Repositories\Eloquent\EarningRepository;
use App\Repositories\FavoriteRepositoryInterface;
use App\Repositories\Eloquent\FavoriteRepository;
use App\Repositories\FaqRepositoryInterface;
use App\Repositories\Eloquent\FaqRepository;
use App\Repositories\FaqCategoryRepositoryInterface;
use App\Repositories\Eloquent\FaqCategoryRepository;
use App\Repositories\FieldRepositoryInterface;
use App\Repositories\Eloquent\FieldRepository;
use App\Repositories\GalleryRepositoryInterface;
use App\Repositories\Eloquent\GalleryRepository;
use App\Repositories\MarketReviewRepositoryInterface;
use App\Repositories\Eloquent\MarketReviewRepository;
use Prettus\Repository\Providers\RepositoryServiceProvider as PrettusRepositoryServiceProvider;
use App\Repositories\RegisterRepositoryInterface;
use App\Repositories\Eloquent\RegisterRepository;
use App\Repositories\CouponRedeemRepositoryInterface;
use App\Repositories\Eloquent\CouponRedeemRepository;
use App\Repositories\CmsPageRepositoryInterface;
use App\Repositories\Eloquent\CmsPageRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(CustomFieldValueRepositoryInterface::class, CustomFieldValueRepository::class);
        $this->app->bind(DeliveryAddressRepositoryInterface::class, DeliveryAddressRepository::class);
        $this->app->bind(DiscountableRepositoryInterface::class, DiscountableRepository::class);
        $this->app->bind(DriverRepositoryInterface::class, DriverRepository::class);
        $this->app->bind(DriverPayoutRepositoryInterface::class, DriverPayoutRepository::class);
        $this->app->bind(EarningRepositoryInterface::class, EarningRepository::class);
        $this->app->bind(FavoriteRepositoryInterface::class, FavoriteRepository::class);
        $this->app->bind(FaqRepositoryInterface::class, FaqRepository::class);
        $this->app->bind(FaqCategoryRepositoryInterface::class, FaqCategoryRepository::class);
        $this->app->bind(FieldRepositoryInterface::class, FieldRepository::class);
        $this->app->bind(GalleryRepositoryInterface::class, GalleryRepository::class);
        $this->app->bind(MarketsCityRepositoryInterface::class, MarketsCityRepository::class);
        $this->app->bind(MarketsPayoutRepositoryInterface::class, MarketsPayoutRepository::class);
        $this->app->bind(MarketReviewRepositoryInterface::class, MarketReviewRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(OptionGroupRepositoryInterface::class, OptionGroupRepository::class);
        $this->app->bind(OptionRepositoryInterface::class, OptionRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(ProductReviewRepositoryInterface::class, ProductReviewRepository::class);
        $this->app->bind(ProductsCityRepositoryInterface::class, ProductsCityRepository::class);
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
        $this->app->bind(ProductsOptionRepositoryInterface::class, ProductsOptionRepository::class);
        $this->app->bind(CouponRepositoryInterface::class, CouponRepository::class);
        $this->app->bind(CurrencyRepositoryInterface::class, CurrencyRepository::class);
        $this->app->bind(CustomFieldRepositoryInterface::class, CustomFieldRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(OrderStatusRepositoryInterface::class, OrderStatusRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(MarketRepositoryInterface::class, MarketRepository::class);
        $this->app->bind(ProductOrderRepositoryInterface::class, ProductOrderRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(SlideRepositoryInterface::class, SlideRepository::class);
        $this->app->bind(StateRepositoryInterface::class, StateRepository::class);
        $this->app->bind(UploadRepositoryInterface::class, UploadRepository::class);
        $this->app->bind(RegisterRepositoryInterface::class, RegisterRepository::class);
        $this->app->bind(CouponRedeemRepositoryInterface::class, CouponRedeemRepository::class);
        $this->app->bind(CmsPageRepositoryInterface::class, CmsPageRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}