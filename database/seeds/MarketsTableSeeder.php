<?php

use Illuminate\Database\Seeder;

class MarketsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


       \DB::table('markets')->delete();
        
      
        $marketNames = array(
            "Endeavor Market",
            "Dynamics Market",
        );

        \DB::table('markets')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'name' => 'DesiSabji Market',
                    'description' => 'Fresh vegetables and fruits from local farmers',
                    'address' => 'SVH 83 Metro Street, Sector 83, Gurugram, Haryana 122004',
                    'latitude' => '28.403670374043354',
                    'longitude' => '76.97335983821192',
                    'phone' => '+91 882 660 0232',
                    'mobile' => '+91 882 660 0232',
                    'information' => '<p>Monday - Saturday 9:00AM - 8:00PM</p><p>Sunday Closed</p>', 
                    'admin_commission' => 15.0,
                    'delivery_fee' => 7.0,
                    'delivery_range' => 7.0,
                    'default_tax' => 8.0,
                    'closed' => 0,
                    'available_for_delivery' => 1,
                    'created_at' => '2019-08-30 11:51:04',
                    'updated_at' => '2020-03-29 17:20:42',
                ),
        ));


    }
}