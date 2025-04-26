<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Market;

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

        // Comment out the factory
        // Market::factory()->times(10)->create();
        
        $marketNames = array(
            "Endeavor Market",
            "Dynamics Market",
            "Galactic Market",
            "Cosmos Market",
            "Flashpoint Market",
            "Market Cap",
            "Great Market",
            "Market Drive",
            "Market Wizard",
            "GoldenGate Market",
            "Market Property",
            "Market Pros",
            "Market More",
            "Pascal Market");

        \DB::table('markets')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'name' => 'Galactic Market',
                    'description' => 'Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                    'address' => '3515 Rosewood Lane Manhattan, NY 10016',
                    'latitude' => '37.42796133580664',
                    'longitude' => '-122.085749655962',
                    'phone' => '+136 226 5669',
                    'mobile' => '+163 525 9432',
                    'information' => '<p>Monday - Thursday 10:00AM - 11:00PM</p><p>Friday - Sunday 12:00PM - 5:00AM</p>',
                    'admin_commission' => 15.0,
                    'delivery_fee' => 7.0,
                    'delivery_range' => 7.0,
                    'default_tax' => 8.0,
                    'closed' => 0,
                    'available_for_delivery' => 1,
                    'created_at' => '2019-08-30 11:51:04',
                    'updated_at' => '2020-03-29 17:20:42',
                ),
            1 =>
                array(
                    'id' => 2,
                    'name' => 'The Lonesome Dove',
                    'description' => 'Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum',
                    'address' => '2911 Corpening Drive South Lyon, MI 48178',
                    'latitude' => '37.42196133580664',
                    'longitude' => '-122.086749655962',
                    'phone' => '+136 226 5669',
                    'mobile' => '+163 525 9432',
                    'information' => '<p>Monday - Thursday 10:00AM - 11:00PM</p><p>Friday - Sunday 12:00PM - 5:00AM</p>',
                    'admin_commission' => 30.0,
                    'delivery_fee' => 5.0,
                    'delivery_range' => 7.0,
                    'default_tax' => 8.0,
                    'closed' => 0,
                    'available_for_delivery' => 1,
                    'created_at' => '2019-08-30 12:15:09',
                    'updated_at' => '2020-03-29 17:36:33',
                ),
            2 =>
                array(
                    'id' => 3,
                    'name' => 'Golden Palace',
                    'description' => 'Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                    'address' => '2572 George Avenue Mobile, AL 36608',
                    'latitude' => '37.4226133580664',
                    'longitude' => '-122.086759655962',
                    'phone' => '+136 226 5669',
                    'mobile' => '+163 525 9432',
                    'information' => '<p>Monday - Thursday 10:00AM - 11:00PM</p><p>Friday - Sunday 12:00PM - 5:00AM<br></p>',
                    'admin_commission' => 10.0,
                    'delivery_fee' => 4.0,
                    'delivery_range' => 7.0,
                    'default_tax' => 8.0,
                    'closed' => 0,
                    'available_for_delivery' => 1,
                    'created_at' => '2019-08-30 12:17:02',
                    'updated_at' => '2020-03-29 17:36:19',
                ),
            3 =>
                array(
                    'id' => 4,
                    'name' => 'La Perla Market',
                    'description' => '<p>Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum<br></p>',
                    'address' => '360 Jody Road Chester Heights, PA 19017',
                    'latitude' => '37.42196233580664',
                    'longitude' => '-122.086743655962',
                    'phone' => '+136 226 5669',
                    'mobile' => '+163 525 9432',
                    'information' => '<p>Monday - Thursday 10:00AM - 11:00PM</p><p>Friday - Sunday 12:00PM - 5:00AM</p>',
                    'admin_commission' => 25.0,
                    'delivery_fee' => 6.0,
                    'delivery_range' => 7.0,
                    'default_tax' => 8.0,
                    'closed' => 0,
                    'available_for_delivery' => 1,
                    'created_at' => '2019-10-09 16:12:20',
                    'updated_at' => '2020-03-29 17:36:09',
                ),
            4 =>
                array(
                    'id' => 5,
                    'name' => 'Market Cap',
                    'description' => 'Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                    'address' => '123 Market Street, San Francisco, CA 94105',
                    'latitude' => '37.7935',
                    'longitude' => '-122.3932',
                    'phone' => '+136 226 5669',
                    'mobile' => '+163 525 9432',
                    'information' => '<p>Monday - Thursday 10:00AM - 11:00PM</p><p>Friday - Sunday 12:00PM - 5:00AM</p>',
                    'admin_commission' => 15.0,
                    'delivery_fee' => 5.0,
                    'delivery_range' => 7.0,
                    'default_tax' => 8.0,
                    'closed' => 0,
                    'available_for_delivery' => 1,
                    'created_at' => '2019-08-30 11:51:04',
                    'updated_at' => '2020-03-29 17:20:42',
                ),
            5 =>
                array(
                    'id' => 6,
                    'name' => 'Great Market',
                    'description' => 'Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                    'address' => '456 Main Street, Boston, MA 02115',
                    'latitude' => '42.3601',
                    'longitude' => '-71.0589',
                    'phone' => '+136 226 5669',
                    'mobile' => '+163 525 9432',
                    'information' => '<p>Monday - Thursday 10:00AM - 11:00PM</p><p>Friday - Sunday 12:00PM - 5:00AM</p>',
                    'admin_commission' => 18.0,
                    'delivery_fee' => 6.0,
                    'delivery_range' => 5.0,
                    'default_tax' => 6.0,
                    'closed' => 0,
                    'available_for_delivery' => 1,
                    'created_at' => '2019-08-30 11:51:04',
                    'updated_at' => '2020-03-29 17:20:42',
                ),
            6 =>
                array(
                    'id' => 7,
                    'name' => 'Market Drive',
                    'description' => 'Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                    'address' => '789 Oak Ave, Chicago, IL 60611',
                    'latitude' => '41.8781',
                    'longitude' => '-87.6298',
                    'phone' => '+136 226 5669',
                    'mobile' => '+163 525 9432',
                    'information' => '<p>Monday - Thursday 10:00AM - 11:00PM</p><p>Friday - Sunday 12:00PM - 5:00AM</p>',
                    'admin_commission' => 20.0,
                    'delivery_fee' => 4.0,
                    'delivery_range' => 8.0,
                    'default_tax' => 9.0,
                    'closed' => 0,
                    'available_for_delivery' => 1,
                    'created_at' => '2019-08-30 11:51:04',
                    'updated_at' => '2020-03-29 17:20:42',
                ),
            7 =>
                array(
                    'id' => 8,
                    'name' => 'Market Wizard',
                    'description' => 'Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                    'address' => '101 Pine Road, Seattle, WA 98101',
                    'latitude' => '47.6062',
                    'longitude' => '-122.3321',
                    'phone' => '+136 226 5669',
                    'mobile' => '+163 525 9432',
                    'information' => '<p>Monday - Thursday 10:00AM - 11:00PM</p><p>Friday - Sunday 12:00PM - 5:00AM</p>',
                    'admin_commission' => 12.0,
                    'delivery_fee' => 3.0,
                    'delivery_range' => 10.0,
                    'default_tax' => 7.5,
                    'closed' => 0,
                    'available_for_delivery' => 1,
                    'created_at' => '2019-08-30 11:51:04',
                    'updated_at' => '2020-03-29 17:20:42',
                ),
            8 =>
                array(
                    'id' => 9,
                    'name' => 'GoldenGate Market',
                    'description' => 'Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                    'address' => '202 Market Lane, Austin, TX 78701',
                    'latitude' => '30.2672',
                    'longitude' => '-97.7431',
                    'phone' => '+136 226 5669',
                    'mobile' => '+163 525 9432',
                    'information' => '<p>Monday - Thursday 10:00AM - 11:00PM</p><p>Friday - Sunday 12:00PM - 5:00AM</p>',
                    'admin_commission' => 14.0,
                    'delivery_fee' => 5.5,
                    'delivery_range' => 6.0,
                    'default_tax' => 8.25,
                    'closed' => 0,
                    'available_for_delivery' => 1,
                    'created_at' => '2019-08-30 11:51:04',
                    'updated_at' => '2020-03-29 17:20:42',
                ),
            9 =>
                array(
                    'id' => 10,
                    'name' => 'Market Property',
                    'description' => 'Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                    'address' => '303 Food Court, Denver, CO 80202',
                    'latitude' => '39.7392',
                    'longitude' => '-104.9903',
                    'phone' => '+136 226 5669',
                    'mobile' => '+163 525 9432',
                    'information' => '<p>Monday - Thursday 10:00AM - 11:00PM</p><p>Friday - Sunday 12:00PM - 5:00AM</p>',
                    'admin_commission' => 16.0,
                    'delivery_fee' => 4.5,
                    'delivery_range' => 9.0,
                    'default_tax' => 7.65,
                    'closed' => 0,
                    'available_for_delivery' => 1,
                    'created_at' => '2019-08-30 11:51:04',
                    'updated_at' => '2020-03-29 17:20:42',
                ),
        ));
    }
}