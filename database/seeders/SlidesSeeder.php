<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Slide;

class SlidesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('slides')->delete();
        Slide::factory()->times(5)->create();
      
        try {
            // Insert permissions and get the IDs
            $permissionNames = [
                'slides.index',
                'slides.create',
                'slides.store',
                'slides.edit',
                'slides.update',
                'slides.destroy',
            ];
            
            $permissionIds = [];
            
            foreach ($permissionNames as $name) {
                // Check if permission already exists
                $existingPermission = \DB::table('permissions')->where('name', $name)->first();
                
                if ($existingPermission) {
                    $permissionIds[] = $existingPermission->id;
                } else {
                    // Insert and get the ID
                    $id = \DB::table('permissions')->insertGetId([
                        'name' => $name,
                        'guard_name' => 'web',
                        'created_at' => '2020-08-23 14:58:02',
                        'updated_at' => '2020-08-23 14:58:02',
                    ]);
                    
                    $permissionIds[] = $id;
                }
            }
            
            // Now insert role_has_permissions with the actual IDs
            $rolePermissions = [];
            foreach ($permissionIds as $permId) {
                $rolePermissions[] = [
                    'permission_id' => $permId,
                    'role_id' => 2,
                ];
            }
            
            if (!empty($rolePermissions)) {
                \DB::table('role_has_permissions')->insert($rolePermissions);
            }
        } catch (\Exception $exception) {
            // Log the exception for debugging
            \Log::error('Error in SlidesSeeder: ' . $exception->getMessage());
        }
        
        try {
            \DB::table('app_settings')->insert([
                ['key' => 'home_section_1', 'value' => 'search'],
                ['key' => 'home_section_2', 'value' => 'slider'],
                ['key' => 'home_section_3', 'value' => 'top_markets_heading'],
                ['key' => 'home_section_4', 'value' => 'top_markets'],
                ['key' => 'home_section_5', 'value' => 'trending_week_heading'],
                ['key' => 'home_section_6', 'value' => 'trending_week'],
                ['key' => 'home_section_7', 'value' => 'categories_heading'],
                ['key' => 'home_section_8', 'value' => 'categories'],
                ['key' => 'home_section_9', 'value' => 'popular_heading'],
                ['key' => 'home_section_10', 'value' => 'popular'],
                ['key' => 'home_section_11', 'value' => 'recent_reviews_heading'],
                ['key' => 'home_section_12', 'value' => 'recent_reviews']
            ]);
        } catch (\Exception $exception) {
            // Log the exception for debugging
            \Log::error('Error in SlidesSeeder app_settings: ' . $exception->getMessage());
        }
    }
}
