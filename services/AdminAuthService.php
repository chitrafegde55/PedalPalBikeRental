<?php

class AdminUser {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function authenticate($username, $password) {
        // Simple authentication - in production, use proper password hashing
        if ($username === 'admin' && $password === 'pedalpal2026') {
            return [
                'id' => 1,
                'username' => 'admin',
                'role' => 'administrator'
            ];
        }
        return false;
    }

    public function createSession($user) {
        $sessionId = bin2hex(random_bytes(32));
        // In a real implementation, store session in database
        $_SESSION['admin_user'] = $user;
        $_SESSION['admin_session'] = $sessionId;
        return $sessionId;
    }

    public function validateSession() {
        return isset($_SESSION['admin_user']) && isset($_SESSION['admin_session']);
    }

    public function getCurrentUser() {
        return $_SESSION['admin_user'] ?? null;
    }

    public function logout() {
        unset($_SESSION['admin_user']);
        unset($_SESSION['admin_session']);
    }
}