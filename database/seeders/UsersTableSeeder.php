<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'login_id' => '8826600232',
                'customer_id' => 'C8826600232',               
                'name' => 'Sachina Yadav',
                'email' => 'algoliveindia@gmail.com',
                'mobile' => '8826600232',                
                'password' => '$2y$10$YOn/Xq6vfvi9oaixrtW8QuM2W0mawkLLqIxL.IoGqrsqOqbIsfBNu',
                'api_token' => 'PivvPlsQWxPl1bB5KrbKNBuraJit0PrUZekQUgtLyTRuyBq921atFtoR1HuA',
                'remember_token' => 'T4PQhFvBcAA7k02f7ejq4I7z7QKKnvxQLV0oqGnuS6Ktz6FdWULrWrzZ3oYn',
                'created_at' => '2018-08-06 22:58:41',
                'updated_at' => '2019-09-27 07:49:45',                
                'braintree_id' => NULL,
                'paypal_email' => NULL,
                'stripe_id' => NULL,
                'card_brand' => NULL,
                'card_last_four' => NULL,
                'trial_ends_at' => NULL,
                'device_token' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'login_id' => '8826708168',
                'customer_id' => 'C8826708168',     
                'name' => 'Sandeep Yadav',
                'email' => 'sandeepjjn@yahoo.com',
                'mobile' => '8826708168', 
                'password' => '$2y$10$YOn/Xq6vfvi9oaixrtW8QuM2W0mawkLLqIxL.IoGqrsqOqbIsfBNu',
                'api_token' => 'tVSfIKRSX2Yn8iAMoUS3HPls84ycS8NAxO2dj2HvePbbr4WHorp4gIFRmFwB',
                'remember_token' => '5nysjzVKI4LU92bjRqMUSYdOaIo1EcPC3pIMb6Tcj2KXSUMriGrIQ1iwRdd0',
                'created_at' => '2018-08-14 17:06:28',
                'updated_at' => '2019-09-25 22:09:35',
                'braintree_id' => NULL,
                'paypal_email' => NULL,
                'stripe_id' => NULL,
                'card_brand' => NULL,
                'card_last_four' => NULL,
                'trial_ends_at' => NULL,
                'device_token' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'login_id' => '1111111111',
                'customer_id' => 'C1111111111',     
                'name' => 'Client 1',
                'email' => 'client1@desisabji.com',
                'mobile' => '1111111111',                
                'password' => '$2y$10$EBubVy3wDbqNbHvMQwkj3OTYVitL8QnHvh/zV0ICVOaSbALy5dD0K',
                'api_token' => 'fXLu7VeYgXDu82SkMxlLPG1mCAXc4EBIx6O5isgYVIKFQiHah0xiOHmzNsBv',
                'remember_token' => 'V6PIUfd8JdHT2zkraTlnBcRSINZNjz5Ou7N0WtUGRyaTweoaXKpSfij6UhqC',
                'created_at' => '2019-10-12 22:31:26',
                'updated_at' => '2020-03-29 17:44:30',
                'braintree_id' => NULL,
                'paypal_email' => NULL,
                'stripe_id' => NULL,
                'card_brand' => NULL,
                'card_last_four' => NULL,
                'trial_ends_at' => NULL,
                'device_token' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'login_id' => '2222222222',
                'customer_id' => 'C2222222222',     
                'name' => 'Client 2',
                'email' => 'client2@desisabji.com',
                'mobile' => '2222222222',                
                'password' => '$2y$10$pmdnepS1FhZUMqOaFIFnNO0spltJpziz3j13UqyEwShmLhokmuoei',
                'api_token' => 'Czrsk9rwD0c75NUPkzNXM2WvbxYHKj8p0nG29pjKT0PZaTgMVzuVyv4hOlte',
                'remember_token' => NULL,
                'created_at' => '2019-10-15 17:55:39',
                'updated_at' => '2020-03-29 17:59:39',
                'braintree_id' => NULL,
                'paypal_email' => NULL,
                'stripe_id' => NULL,
                'card_brand' => NULL,
                'card_last_four' => NULL,
                'trial_ends_at' => NULL,
                'device_token' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'login_id' => '3333333333',
                'customer_id' => 'C3333333333',     
                'name' => 'Ravi Kumar',
                'email' => 'driver1@desisabji.com',
                'mobile' => '3333333333',                
                'password' => '$2y$10$T/jwzYDJfC8c9CdD5PbpuOKvEXlpv4.RR1jMT0PgIMT.fzeGw67JO',
                'api_token' => 'OuMsmU903WMcMhzAbuSFtxBekZVdXz66afifRo3YRCINi38jkXJ8rpN0FcfS',
                'remember_token' => NULL,
                'created_at' => '2019-12-15 18:49:44',
                'updated_at' => '2020-03-29 17:22:10',
                'braintree_id' => NULL,
                'paypal_email' => NULL,
                'stripe_id' => NULL,
                'card_brand' => NULL,
                'card_last_four' => NULL,
                'trial_ends_at' => NULL,
                'device_token' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'login_id' => '4444444444',
                'customer_id' => 'C4444444444',     
                'name' => 'Kirshan',
                'email' => 'driver2@desisabji.com',
                'mobile' => '4444444444',                
                'password' => '$2y$10$YF0jCx2WCQtfZOq99hR8kuXsAE0KSnu5OYSomRtI9iCVguXDoDqVm',
                'api_token' => 'zh9mzfNO2iPtIxj6k4Jpj8flaDyOsxmlGRVUZRnJqOGBr8IuDyhb3cGoncvS',
                'remember_token' => NULL,
                'created_at' => '2020-03-29 17:28:04',
                'updated_at' => '2020-03-29 17:28:04',
                'braintree_id' => NULL,
                'paypal_email' => NULL,
                'stripe_id' => NULL,
                'card_brand' => NULL,
                'card_last_four' => NULL,
                'trial_ends_at' => NULL,
                'device_token' => NULL,
            ),
        ));
        
        
    }
}