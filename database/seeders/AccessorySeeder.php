<?php

namespace Database\Seeders;

use App\Models\Accessory;
use Illuminate\Database\Seeder;

class AccessorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accessories = [
            [
                'name' => 'Trail Blazer Water Bottle',
                'category' => 'Hydration',
                'description' => 'Keeps your water cold and your excuses for stopping minimal.',
                'unit_price' => 2.99,
                'stock_count' => 15,
                'bike_compatibility' => ['mountain', 'beach'],
            ],
            [
                'name' => 'Wicker Beach Basket',
                'category' => 'Storage',
                'description' => 'Holds your picnic, your sunscreen, and one very small dog.',
                'unit_price' => 4.99,
                'stock_count' => 8,
                'bike_compatibility' => ['beach'],
            ],
            [
                'name' => 'NightRider Bike Light',
                'category' => 'Safety',
                'description' => 'Because riding in the dark and just hoping for the best is not a strategy.',
                'unit_price' => 3.49,
                'stock_count' => 20,
                'bike_compatibility' => ['mountain', 'beach'],
            ],
            [
                'name' => 'Summit Cargo Basket',
                'category' => 'Storage',
                'description' => 'Straps to your frame. Carries snacks. Does not carry your feelings.',
                'unit_price' => 5.99,
                'stock_count' => 6,
                'bike_compatibility' => ['mountain'],
            ],
        ];

        foreach ($accessories as $accessory) {
            Accessory::create($accessory);
        }
    }
}