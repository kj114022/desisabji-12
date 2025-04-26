<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DriverMarketsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('driver_markets')->delete();

        // First check which market IDs actually exist
        $marketIds = \DB::table('markets')->pluck('id')->toArray();
        
        // Only proceed if we have markets
        if (count($marketIds) > 0) {
            $driverMarkets = [];
            
            // Use existing market IDs
            if (in_array(1, $marketIds)) $driverMarkets[] = ['user_id' => 5, 'market_id' => 1];
            if (in_array(2, $marketIds)) $driverMarkets[] = ['user_id' => 5, 'market_id' => 2];
            if (in_array(3, $marketIds)) {
                $driverMarkets[] = ['user_id' => 6, 'market_id' => 3];
            } else if (count($marketIds) >= 3) {
                $driverMarkets[] = ['user_id' => 6, 'market_id' => $marketIds[2]];
            }
            
            // Add more as needed, checking for existence
            
            if (!empty($driverMarkets)) {
                \DB::table('driver_markets')->insert($driverMarkets);
            }
        }
    }
}