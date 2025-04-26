<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // First seed dependencies
            UsersTableSeeder::class,
            CustomFieldsTableSeeder::class,
            FieldsTableSeeder::class,
            
            // Then seed the markets
            MarketsTableSeeder::class,
            CustomFieldValuesTableSeeder::class,
            
            // Core app settings and basic data
            AppSettingsTableSeeder::class,
            CategoriesTableSeeder::class,
            FaqCategoriesTableSeeder::class,
            OrderStatusesTableSeeder::class,
            CurrenciesTableSeeder::class,
            OptionGroupsTableSeeder::class,
            
            // Products related seeders
            ProductsTableSeeder::class,
            GalleriesTableSeeder::class,
            ProductReviewsTableSeeder::class,
            MarketReviewsTableSeeder::class,
            PaymentsTableSeeder::class,
            
            // User related seeders
            DeliveryAddressesTableSeeder::class,
            OrdersTableSeeder::class,
            CartsTableSeeder::class,
            OptionsTableSeeder::class,
            NotificationsTableSeeder::class,
            FaqsTableSeeder::class,
            FavoritesTableSeeder::class,
            
            // Relationship seeders
            ProductOrdersTableSeeder::class,
            CartOptionsTableSeeder::class,
            UserMarketsTableSeeder::class,
            DriverMarketsTableSeeder::class,
            ProductOrderOptionsTableSeeder::class,
            FavoriteOptionsTableSeeder::class,
            MarketFieldsTableSeeder::class,
            
            // Permission related seeders
            RolesTableSeeder::class,
            DemoPermissionsPermissionsTableSeeder::class,
            ModelHasPermissionsTableSeeder::class,
            ModelHasRolesTableSeeder::class,
            RoleHasPermissionsTableSeeder::class,
            
            // Media and uploads
            MediaTableSeeder::class,
            UploadsTableSeeder::class,
            
            // Driver related seeders
            DriversTableSeeder::class,
            EarningsTableSeeder::class,
            DriversPayoutsTableSeeder::class,
            MarketsPayoutsTableSeeder::class,
            
            // Additional features
            CouponPermission::class,
            SlidesSeeder::class,
        ]);
    }
}
