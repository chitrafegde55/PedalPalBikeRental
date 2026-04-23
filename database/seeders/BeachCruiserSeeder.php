<?php

namespace Database\Seeders;

use App\Models\BeachCruiser;
use Illuminate\Database\Seeder;

class BeachCruiserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $beachCruisers = [
            [
                'bike_id' => '1',
                'model_name' => 'Sunset Drifter',
                'color' => 'Coral',
                'frame_size' => 'Medium',
                'daily_rate' => 14.99,
                'is_available' => true,
            ],
            [
                'bike_id' => '2',
                'model_name' => 'Ocean Breeze',
                'color' => 'Teal',
                'frame_size' => 'Large',
                'daily_rate' => 16.99,
                'is_available' => true,
            ],
            [
                'bike_id' => '3',
                'model_name' => 'Sandy Shores',
                'color' => 'Cream',
                'frame_size' => 'Small',
                'daily_rate' => 12.99,
                'is_available' => false,
            ],
            [
                'bike_id' => '4',
                'model_name' => 'Tropical Wave',
                'color' => 'Lime Green',
                'frame_size' => 'Medium',
                'daily_rate' => 15.99,
                'is_available' => true,
            ],
            [
                'bike_id' => '5',
                'model_name' => 'Breezy Blue',
                'color' => 'Sky Blue',
                'frame_size' => 'Large',
                'daily_rate' => 17.99,
                'is_available' => true,
            ],
            [
                'bike_id' => '6',
                'model_name' => 'Flamingo Glide',
                'color' => 'Hot Pink',
                'frame_size' => 'Small',
                'daily_rate' => 13.99,
                'is_available' => false,
            ],
        ];

        foreach ($beachCruisers as $cruiser) {
            BeachCruiser::create($cruiser);
        }
    }
}