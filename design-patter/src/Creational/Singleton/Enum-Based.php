<?php

// Why Use Enums for Singleton?

// ✅ Thread-Safe: PHP ensures that enum cases are instantiated only once.
// ✅ Lazy Initialization: The singleton instance is created only when first accessed.
// ✅ No Need for Locks: Avoids synchronized methods or static properties.
// ✅ Readable & Clean Code: No complex logic to manage singleton instances.


enum LoggerSingleton {
    case INSTANCE;

    private static ?\DateTime $timestamp = null;

    public function log(string $message): void {
        if (self::$timestamp === null) {
            self::$timestamp = new \DateTime();
        }
        echo "[" . self::$timestamp->format('Y-m-d H:i:s') . "] " . $message . "\n";
    }
}

// Usage
LoggerSingleton::INSTANCE->log("Application started...");
LoggerSingleton::INSTANCE->log("User logged in.");
