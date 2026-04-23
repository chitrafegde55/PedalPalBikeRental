<?php

require_once __DIR__ . '/../data/Database.php';
require_once __DIR__ . '/../models/BeachCruiser.php';
require_once __DIR__ . '/../models/MountainBike.php';
require_once __DIR__ . '/../models/Accessory.php';

class AdminHandler {
    private $db;
    private $beachCruiserModel;
    private $mountainBikeModel;
    private $accessoryModel;

    public function __construct() {
        $this->db = new Database();
        $this->beachCruiserModel = new BeachCruiser($this->db->getConnection());
        $this->mountainBikeModel = new MountainBike($this->db->getConnection());
        $this->accessoryModel = new Accessory($this->db->getConnection());
    }

    public function handleReset() {
        try {
            // Reset all inventory to defaults
            $this->beachCruiserModel->resetToDefaults();
            $this->mountainBikeModel->resetToDefaults();
            $this->accessoryModel->resetToDefaults();

            echo json_encode([
                'success' => true,
                'message' => 'All inventory has been reset to default values'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to reset inventory: ' . $e->getMessage()
            ]);
        }
    }

    public function handleReturnBike($bikeId) {
        try {
            // Get bike details to determine type
            $pdo = $this->db->getConnection();

            // Check if it's a beach cruiser
            $stmt = $pdo->prepare("SELECT id FROM beach_cruisers WHERE id = ?");
            $stmt->execute([$bikeId]);
            if ($stmt->fetch()) {
                $this->beachCruiserModel->returnBike($bikeId);
                echo json_encode([
                    'success' => true,
                    'message' => 'Beach cruiser returned successfully'
                ]);
                return;
            }

            // Check if it's a mountain bike
            $stmt = $pdo->prepare("SELECT id FROM mountain_bikes WHERE id = ?");
            $stmt->execute([$bikeId]);
            if ($stmt->fetch()) {
                $this->mountainBikeModel->returnBike($bikeId);
                echo json_encode([
                    'success' => true,
                    'message' => 'Mountain bike returned successfully'
                ]);
                return;
            }

            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Bike not found'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to return bike: ' . $e->getMessage()
            ]);
        }
    }

    public function handleGetActivity() {
        try {
            // In a real implementation, this would track rental/return activity
            // For now, return mock data
            $activity = [
                [
                    'id' => 1,
                    'type' => 'rental',
                    'item_type' => 'bike',
                    'item_name' => 'Beach Cruiser Classic',
                    'timestamp' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                    'user' => 'Customer'
                ],
                [
                    'id' => 2,
                    'type' => 'return',
                    'item_type' => 'bike',
                    'item_name' => 'Mountain Bike Trail',
                    'timestamp' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                    'user' => 'Staff'
                ],
                [
                    'id' => 3,
                    'type' => 'order',
                    'item_type' => 'accessory',
                    'item_name' => 'Water Bottle + Bike Light',
                    'timestamp' => date('Y-m-d H:i:s', strtotime('-30 minutes')),
                    'user' => 'Customer'
                ]
            ];

            echo json_encode([
                'success' => true,
                'data' => $activity,
                'message' => 'Recent activity retrieved'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to get activity: ' . $e->getMessage()
            ]);
        }
    }
}