<?php

class Database {
    private $pdo;
    private $dbPath;

    public function __construct() {
        $this->dbPath = __DIR__ . '/../database/database.sqlite';

        // Ensure the database directory exists
        $dbDir = dirname($this->dbPath);
        if (!is_dir($dbDir)) {
            mkdir($dbDir, 0755, true);
        }

        $this->pdo = new PDO('sqlite:' . $this->dbPath);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function initializeTables() {
        // Create tables if they don't exist
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS beach_cruisers (
                id INTEGER PRIMARY KEY,
                name TEXT NOT NULL,
                description TEXT,
                price_per_hour REAL NOT NULL,
                availability INTEGER NOT NULL DEFAULT 1
            )
        ");

        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS mountain_bikes (
                id INTEGER PRIMARY KEY,
                name TEXT NOT NULL,
                description TEXT,
                price_per_hour REAL NOT NULL,
                availability INTEGER NOT NULL DEFAULT 1
            )
        ");

        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS accessories (
                id INTEGER PRIMARY KEY,
                name TEXT NOT NULL,
                description TEXT,
                price REAL NOT NULL,
                stock INTEGER NOT NULL DEFAULT 0
            )
        ");
    }
}