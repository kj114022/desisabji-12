<?php

use Illuminate\Database\Seeder;

class MarketFieldsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('market_fields')->delete();

        // Get existing market and field IDs dynamically
        $marketIds = \App\Models\Market::pluck('id')->toArray();
        $fieldIds = \App\Models\Field::pluck('id')->toArray();

        // Only proceed if we have markets and fields
        if (empty($marketIds) || empty($fieldIds)) {
            return;
        }

        // Use only existing market IDs (currently only market_id 1 exists)
        $marketId = $marketIds[0]; // Use the first available market

        // Create market-field relationships using existing field IDs
        $data = [];
        foreach ($fieldIds as $fieldId) {
            $data[] = [
                'market_id' => $marketId,
                'field_id' => $fieldId,
            ];
        }

        if (!empty($data)) {
            \DB::table('market_fields')->insert($data);
        }
    }
}
