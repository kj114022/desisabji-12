<?php

use Illuminate\Database\Seeder;

class UserMarketsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        \DB::table('user_markets')->delete();

        // Get existing market and user IDs dynamically
        $marketIds = \App\Models\Market::pluck('id')->toArray();
        $userIds = \App\Models\User::pluck('id')->toArray();

        // Only proceed if we have markets and users
        if (empty($marketIds) || empty($userIds)) {
            return;
        }

        // Use only existing market IDs (currently only market_id 1 exists)
        $marketId = $marketIds[0]; // Use the first available market

        // Create user-market relationships for existing users
        $data = [];
        foreach ($userIds as $userId) {
            $data[] = [
                'user_id' => $userId,
                'market_id' => $marketId,
            ];
        }

        if (!empty($data)) {
            \DB::table('user_markets')->insert($data);
        }
    }
}