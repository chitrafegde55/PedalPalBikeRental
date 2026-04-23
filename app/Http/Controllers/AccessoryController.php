<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AccessoryController extends Controller
{
    /**
     * Get accessories with optional bike type filter
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $bikeType = $request->query('bikeType');
            $query = Accessory::query();

            if ($bikeType && in_array($bikeType, ['beach', 'mountain'])) {
                $query->whereJsonContains('bike_compatibility', $bikeType);
            }

            $accessories = $query->get();

            $formattedAccessories = $accessories->map(function ($accessory) {
                return [
                    'id' => $accessory->id,
                    'name' => $accessory->name,
                    'category' => $accessory->category,
                    'description' => $accessory->description,
                    'unit_price' => (float) $accessory->unit_price,
                    'stock_count' => (int) $accessory->stock_count,
                    'bike_compatibility' => $accessory->bike_compatibility,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedAccessories,
                'message' => 'Accessories retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve accessories: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process accessory order
     */
    public function order(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'items' => 'required|array|min:1',
                'items.*.id' => 'required|integer|exists:accessories,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            $total = 0;
            $discountApplied = false;
            $orderedItems = [];

            // Check for bundle discount (Water Bottle + Bike Light)
            $hasWaterBottle = false;
            $hasBikeLight = false;

            foreach ($validated['items'] as $item) {
                $accessory = Accessory::find($item['id']);

                if ($accessory->stock_count < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for {$accessory->name}. Available: {$accessory->stock_count}"
                    ], 409);
                }

                // Check for bundle items
                if (strtolower($accessory->name) === 'trail blazer water bottle') {
                    $hasWaterBottle = true;
                }
                if (strtolower($accessory->name) === 'nightrider bike light') {
                    $hasBikeLight = true;
                }

                $subtotal = $accessory->unit_price * $item['quantity'];
                $total += $subtotal;

                $orderedItems[] = [
                    'id' => $accessory->id,
                    'name' => $accessory->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => (float) $accessory->unit_price,
                    'subtotal' => (float) $subtotal,
                ];
            }

            // Apply 10% bundle discount if both items are present
            if ($hasWaterBottle && $hasBikeLight) {
                $discountApplied = true;
                $total *= 0.9; // 10% discount
            }

            // Deduct stock
            foreach ($validated['items'] as $item) {
                $accessory = Accessory::find($item['id']);
                $accessory->decrement('stock_count', $item['quantity']);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $orderedItems,
                    'subtotal' => array_sum(array_column($orderedItems, 'subtotal')),
                    'discount_applied' => $discountApplied,
                    'discount_amount' => $discountApplied ? array_sum(array_column($orderedItems, 'subtotal')) * 0.1 : 0,
                    'total' => (float) $total,
                ],
                'message' => 'Order processed successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process order: ' . $e->getMessage()
            ], 500);
        }
    }
}