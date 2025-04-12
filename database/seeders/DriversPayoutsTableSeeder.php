<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DriversPayoutsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('drivers_payouts')->delete();
    }
}