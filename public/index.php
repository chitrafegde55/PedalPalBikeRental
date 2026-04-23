<?php

// Entry point for PedalPal Bike Rental API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Simple routing for API endpoints
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
$path = parse_url($requestUri, PHP_URL_PATH);

// Remove query string for routing
$path = strtok($path, '?');

if (strpos($path, '/api/') === 0) {
    $apiPath = substr($path, 5); // Remove '/api/' prefix

    // Route to appropriate handler
    if ($apiPath === 'bikes' && $requestMethod === 'GET') {
        // Check for type filter in query string
        $queryParams = [];
        parse_str(parse_url($requestUri, PHP_URL_QUERY), $queryParams);
        $type = $queryParams['type'] ?? 'beach'; // Default to beach
        
        $_GET['action'] = $type;
        require_once __DIR__ . '/../handlers/bike-handler.php';
    } elseif (preg_match('/^bikes\/(\d+)\/rent$/', $apiPath, $matches) && $requestMethod === 'POST') {
        $_GET['action'] = 'rent';
        $_GET['bikeId'] = $matches[1];
        $_GET['bikeType'] = 'beach'; // For now, assume beach cruiser - could be enhanced
        require_once __DIR__ . '/../handlers/bike-handler.php';
    } elseif ($apiPath === 'accessories' && $requestMethod === 'GET') {
        $_GET['action'] = 'list';
        require_once __DIR__ . '/../handlers/accessory-handler.php';
    } elseif ($apiPath === 'accessories/order' && $requestMethod === 'POST') {
        // The accessory handler checks REQUEST_METHOD internally
        require_once __DIR__ . '/../handlers/accessory-handler.php';
    } elseif ($apiPath === 'admin/reset' && $requestMethod === 'POST') {
        // Require authentication for admin actions
        require_once __DIR__ . '/../handlers/auth-handler.php';
        $authHandler = new AuthHandler();
        $authHandler->requireAuth();

        require_once __DIR__ . '/../handlers/admin-handler.php';
        $handler = new AdminHandler();
        $handler->handleReset();
    } elseif (preg_match('/^admin\/bikes\/(\d+)\/return$/', $apiPath, $matches) && $requestMethod === 'POST') {
        require_once __DIR__ . '/../handlers/auth-handler.php';
        $authHandler = new AuthHandler();
        $authHandler->requireAuth();

        require_once __DIR__ . '/../handlers/admin-handler.php';
        $handler = new AdminHandler();
        $handler->handleReturnBike($matches[1]);
    } elseif ($apiPath === 'admin/activity' && $requestMethod === 'GET') {
        require_once __DIR__ . '/../handlers/auth-handler.php';
        $authHandler = new AuthHandler();
        $authHandler->requireAuth();

        require_once __DIR__ . '/../handlers/admin-handler.php';
        $handler = new AdminHandler();
        $handler->handleGetActivity();
    } elseif ($apiPath === 'auth/login' && $requestMethod === 'POST') {
        require_once __DIR__ . '/../handlers/auth-handler.php';
        $handler = new AuthHandler();
        $handler->handleLogin();
    } elseif ($apiPath === 'auth/logout' && $requestMethod === 'POST') {
        require_once __DIR__ . '/../handlers/auth-handler.php';
        $handler = new AuthHandler();
        $handler->handleLogout();
    } elseif ($apiPath === 'auth/check' && $requestMethod === 'GET') {
        require_once __DIR__ . '/../handlers/auth-handler.php';
        $handler = new AuthHandler();
        $handler->handleCheckAuth();
    } else {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'API endpoint not found'
        ]);
    }
} else {
    // Serve the Vue app for non-API routes
    if ($path === '/' || $path === '/index.html') {
        // This will be handled by the static HTML file
        // But since we're in PHP, return a redirect or serve the HTML
        header('Content-Type: text/html');
        readfile(__DIR__ . '/index.html');
    } else {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Route not found'
        ]);
    }
}