<?php

namespace Database\Seeders;

use App\Models\MountainBike;
use Illuminate\Database\Seeder;

class MountainBikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mountainBikes = [
            [
                'bike_id' => '101',
                'model_name' => 'TrailBlazer X9',
                'brand' => 'ApexRide',
                'gear_count' => 21,
                'suspension_type' => 'Full',
                'frame_material' => 'Aluminum',
                'terrain' => 'All-Mountain',
                'weight_kg' => 13.5,
                'daily_rate' => 24.99,
                'is_available' => false,
            ],
            [
                'bike_id' => '102',
                'model_name' => 'Summit Shredder',
                'brand' => 'PeakForce',
                'gear_count' => 27,
                'suspension_type' => 'Full',
                'frame_material' => 'Carbon Fiber',
                'terrain' => 'Enduro',
                'weight_kg' => 11.2,
                'daily_rate' => 34.99,
                'is_available' => false,
            ],
            [
                'bike_id' => '103',
                'model_name' => 'Canyon Crusher',
                'brand' => 'TerraRide',
                'gear_count' => 18,
                'suspension_type' => 'Hardtail',
                'frame_material' => 'Steel',
                'terrain' => 'Cross-Country',
                'weight_kg' => 14.8,
                'daily_rate' => 19.99,
                'is_available' => false,
            ],
            [
                'bike_id' => '104',
                'model_name' => 'Ridge Runner',
                'brand' => 'ApexRide',
                'gear_count' => 24,
                'suspension_type' => 'Hardtail',
                'frame_material' => 'Aluminum',
                'terrain' => 'Trail',
                'weight_kg' => 12.9,
                'daily_rate' => 22.99,
                'is_available' => false,
            ],
        ];

        foreach ($mountainBikes as $bike) {
            MountainBike::create($bike);
        }
    }
}