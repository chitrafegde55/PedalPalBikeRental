<?php

// Simple database setup script for Phase 1
// This would normally be done with Laravel migrations and seeders

echo "PedalPal Database Setup - Phase 1\n";
echo "=================================\n\n";

// Check if we can connect to SQLite
try {
    $dbPath = __DIR__ . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'database.sqlite';
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✓ Connected to SQLite database\n";

    // Create tables (simplified version of migrations)
    $tables = [
        "CREATE TABLE IF NOT EXISTS beach_cruisers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            bike_id VARCHAR(255) UNIQUE NOT NULL,
            model_name VARCHAR(255) NOT NULL,
            color VARCHAR(255) NOT NULL,
            frame_size VARCHAR(255) NOT NULL,
            daily_rate DECIMAL(8,2) NOT NULL,
            is_available BOOLEAN DEFAULT 1
        )",

        "CREATE TABLE IF NOT EXISTS mountain_bikes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            bike_id VARCHAR(255) UNIQUE NOT NULL,
            model_name VARCHAR(255) NOT NULL,
            brand VARCHAR(255) NOT NULL,
            gear_count INTEGER NOT NULL,
            suspension_type VARCHAR(255) NOT NULL,
            frame_material VARCHAR(255) NOT NULL,
            terrain VARCHAR(255) NOT NULL,
            weight_kg DECIMAL(5,1) NOT NULL,
            daily_rate DECIMAL(8,2) NOT NULL,
            is_available BOOLEAN DEFAULT 1
        )",

        "CREATE TABLE IF NOT EXISTS accessories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL,
            category VARCHAR(255) NOT NULL,
            description TEXT,
            unit_price DECIMAL(8,2) NOT NULL,
            stock_count INTEGER DEFAULT 0,
            bike_compatibility TEXT
        )"
    ];

    foreach ($tables as $sql) {
        $pdo->exec($sql);
    }

    echo "✓ Database tables created\n";

    // Seed data
    $beachCruisers = [
        ['1', 'Sunset Drifter', 'Coral', 'Medium', 14.99, 1],
        ['2', 'Ocean Breeze', 'Teal', 'Large', 16.99, 1],
        ['3', 'Sandy Shores', 'Cream', 'Small', 12.99, 0],
        ['4', 'Tropical Wave', 'Lime Green', 'Medium', 15.99, 1],
        ['5', 'Breezy Blue', 'Sky Blue', 'Large', 17.99, 1],
        ['6', 'Flamingo Glide', 'Hot Pink', 'Small', 13.99, 0],
    ];

    $stmt = $pdo->prepare("INSERT OR REPLACE INTO beach_cruisers
        (bike_id, model_name, color, frame_size, daily_rate, is_available) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($beachCruisers as $bike) {
        $stmt->execute($bike);
    }

    echo "✓ Beach cruisers seeded\n";

    $mountainBikes = [
        ['101', 'TrailBlazer X9', 'ApexRide', 21, 'Full', 'Aluminum', 'All-Mountain', 13.5, 24.99, 0],
        ['102', 'Summit Shredder', 'PeakForce', 27, 'Full', 'Carbon Fiber', 'Enduro', 11.2, 34.99, 0],
        ['103', 'Canyon Crusher', 'TerraRide', 18, 'Hardtail', 'Steel', 'Cross-Country', 14.8, 19.99, 0],
        ['104', 'Ridge Runner', 'ApexRide', 24, 'Hardtail', 'Aluminum', 'Trail', 12.9, 22.99, 0],
    ];

    $stmt = $pdo->prepare("INSERT OR REPLACE INTO mountain_bikes
        (bike_id, model_name, brand, gear_count, suspension_type, frame_material, terrain, weight_kg, daily_rate, is_available)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    foreach ($mountainBikes as $bike) {
        $stmt->execute($bike);
    }

    echo "✓ Mountain bikes seeded\n";

    $accessories = [
        ['Trail Blazer Water Bottle', 'Hydration', 'Keeps your water cold and your excuses for stopping minimal.', 2.99, 15, '["mountain","beach"]'],
        ['Wicker Beach Basket', 'Storage', 'Holds your picnic, your sunscreen, and one very small dog.', 4.99, 8, '["beach"]'],
        ['NightRider Bike Light', 'Safety', 'Because riding in the dark and just hoping for the best is not a strategy.', 3.49, 20, '["mountain","beach"]'],
        ['Summit Cargo Basket', 'Storage', 'Straps to your frame. Carries snacks. Does not carry your feelings.', 5.99, 6, '["mountain"]'],
    ];

    $stmt = $pdo->prepare("INSERT OR REPLACE INTO accessories
        (name, category, description, unit_price, stock_count, bike_compatibility) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($accessories as $accessory) {
        $stmt->execute($accessory);
    }

    echo "✓ Accessories seeded\n";

    echo "\n🎉 Database setup complete!\n";
    echo "Phase 1 API endpoints are ready for testing.\n";

} catch (Exception $e) {
    echo "❌ Database setup failed: " . $e->getMessage() . "\n";
}