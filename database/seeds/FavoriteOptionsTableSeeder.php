<?php

use Illuminate\Database\Seeder;

class FavoriteOptionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('favorite_options')->delete();

        // Get existing favorite and option IDs dynamically
        $favoriteIds = \App\Models\Favorite::pluck('id')->toArray();
        $optionIds = \App\Models\Option::pluck('id')->toArray();

        // Only proceed if we have favorites and options
        if (empty($favoriteIds) || empty($optionIds)) {
            return;
        }

        // Create favorite-option relationships using existing IDs
        $data = [];
        foreach ($favoriteIds as $favoriteId) {
            // Assign a random option to each favorite
            $optionId = $optionIds[array_rand($optionIds)];
            $data[] = [
                'favorite_id' => $favoriteId,
                'option_id' => $optionId,
            ];
        }

        if (!empty($data)) {
            \DB::table('favorite_options')->insert($data);
        }
        
        
    }
}