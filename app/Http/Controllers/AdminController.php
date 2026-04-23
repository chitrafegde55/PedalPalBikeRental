<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\BeachCruiser;
use App\Models\MountainBike;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    /**
     * Reset all inventory data to defaults
     */
    public function reset(Request $request): JsonResponse
    {
        try {
            // Reset beach cruisers availability
            BeachCruiser::query()->update(['is_available' => true]);

            // Reset mountain bikes availability
            MountainBike::query()->update(['is_available' => true]);

            // Reset accessory stock to default values
            $defaultStock = [
                ['name' => 'Trail Blazer Water Bottle', 'stock' => 15],
                ['name' => 'Wicker Beach Basket', 'stock' => 8],
                ['name' => 'NightRider Bike Light', 'stock' => 20],
                ['name' => 'Summit Cargo Basket', 'stock' => 6],
            ];

            foreach ($defaultStock as $item) {
                Accessory::where('name', $item['name'])
                        ->update(['stock_count' => $item['stock']]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'beach_cruisers_reset' => BeachCruiser::count(),
                    'mountain_bikes_reset' => MountainBike::count(),
                    'accessories_reset' => Accessory::count(),
                ],
                'message' => 'All inventory data has been reset to defaults'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset inventory: ' . $e->getMessage()
            ], 500);
        }
    }
}