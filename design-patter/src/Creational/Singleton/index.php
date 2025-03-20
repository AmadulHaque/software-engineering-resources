<?php

// Think of Singleton like having only one remote control for your TV that everyone in the house 
// must share. No matter who asks for the remote, they all get the same one.

use Threaded;
use Mutex;

class DatabaseConnection extends Threaded {
    private static ?DatabaseConnection $instance = null;
    public static $mutex;
    private $connection;

    // Private constructor to prevent direct instantiation
    private function __construct() {
        echo "Connecting to database...\n";
        $this->connection = "Database connected!";
    }

    // Thread-safe Singleton method with Mutex
    public static function getInstance(): DatabaseConnection {
        if (self::$instance === null) {
            // Lock the mutex
            Mutex::lock(self::$mutex);
            
            // Double-check to avoid race conditions
            if (self::$instance === null) {
                self::$instance = new self();
            }
            // Unlock the mutex
            Mutex::unlock(self::$mutex);
        }

        return self::$instance;
    }

    public function query(string $sql) {
        echo "Running query: $sql\n";
        return "Query results";
    }

    // Prevent cloning
    private function __clone() {}

    // Prevent unserialization
    public function __wakeup() {
        throw new \Exception("Cannot unserialize a Singleton.");
    }
}

// Initialize mutex before usage
DatabaseConnection::$mutex = Mutex::create();

// Usage
$db1 = DatabaseConnection::getInstance();
$db1->query("SELECT * FROM users");

$db2 = DatabaseConnection::getInstance();
$db2->query("SELECT * FROM products");