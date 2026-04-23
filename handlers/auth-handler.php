<?php

require_once __DIR__ . '/../services/AdminAuthService.php';
require_once __DIR__ . '/../data/Database.php';

class AuthHandler {
    private $authService;

    public function __construct() {
        session_start();
        $db = new Database();
        $this->authService = new AdminUser($db->getConnection());
    }

    public function handleLogin() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !isset($input['username']) || !isset($input['password'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Username and password are required'
            ]);
            return;
        }

        $user = $this->authService->authenticate($input['username'], $input['password']);

        if ($user) {
            $sessionId = $this->authService->createSession($user);
            echo json_encode([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'session_id' => $sessionId
                ],
                'message' => 'Login successful'
            ]);
        } else {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Invalid credentials'
            ]);
        }
    }

    public function handleLogout() {
        $this->authService->logout();
        echo json_encode([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }

    public function handleCheckAuth() {
        if ($this->authService->validateSession()) {
            echo json_encode([
                'success' => true,
                'data' => [
                    'user' => $this->authService->getCurrentUser()
                ],
                'message' => 'Authenticated'
            ]);
        } else {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Not authenticated'
            ]);
        }
    }

    public function requireAuth() {
        if (!$this->authService->validateSession()) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Authentication required'
            ]);
            exit;
        }
    }
}