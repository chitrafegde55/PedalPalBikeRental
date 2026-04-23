<?php

namespace App\Http\Controllers;

use App\Models\BeachCruiser;
use App\Models\MountainBike;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BikeController extends Controller
{
    /**
     * Get bikes by type
     */
    public function index(Request $request): JsonResponse
    {
        $type = $request->query('type');

        try {
            if ($type === 'beach') {
                $bikes = BeachCruiser::all();
                $formattedBikes = $bikes->map(function ($bike) {
                    return [
                        'bike_id' => (int) $bike->bike_id,
                        'model_name' => $bike->model_name,
                        'color' => $bike->color,
                        'frame_size' => $bike->frame_size,
                        'daily_rate' => (float) $bike->daily_rate,
                        'is_available' => (bool) $bike->is_available,
                    ];
                });
            } elseif ($type === 'mountain') {
                $bikes = MountainBike::all();
                $formattedBikes = $bikes->map(function ($bike) {
                    return [
                        'BikeID' => (int) $bike->bike_id,
                        'ModelName' => $bike->model_name,
                        'Brand' => $bike->brand,
                        'GearCount' => (int) $bike->gear_count,
                        'SuspensionType' => $bike->suspension_type,
                        'FrameMaterial' => $bike->frame_material,
                        'DailyRate' => (float) $bike->daily_rate,
                        'IsAvailable' => (bool) $bike->is_available,
                        'Terrain' => $bike->terrain,
                        'WeightKg' => (float) $bike->weight_kg,
                    ];
                });
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid bike type. Use "beach" or "mountain".'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'data' => $formattedBikes,
                'message' => 'Bikes retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve bikes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rent a specific bike
     */
    public function rent(Request $request, string $id): JsonResponse
    {
        try {
            // Check if it's a beach cruiser or mountain bike
            $bike = BeachCruiser::where('bike_id', $id)->first();

            if (!$bike) {
                $bike = MountainBike::where('bike_id', $id)->first();
            }

            if (!$bike) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bike not found'
                ], 404);
            }

            if (!$bike->is_available) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bike is not available for rent'
                ], 409);
            }

            // Mark as unavailable
            $bike->update(['is_available' => false]);

            return response()->json([
                'success' => true,
                'data' => [
                    'bike_id' => $id,
                    'is_available' => false
                ],
                'message' => 'Bike rented successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to rent bike: ' . $e->getMessage()
            ], 500);
        }
    }
}