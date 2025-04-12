<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VendorOrder;

class VendorOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VendorOrder::factory()->times(50)->create();
    }
}
