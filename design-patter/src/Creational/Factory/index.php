<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Symfony\Component\VarDumper\VarDumper;

// This is like a pizza shop. You ask for a specific type of pizza (pepperoni, veggie, etc.), 
// and the kitchen (factory) makes it for you. You don't need to know how the pizza is made.

class FileLogger {
    public function log($message) {
        dump("Writing to file: $message");
    }
}

class DatabaseLogger {
    public function log($message) {
        dump("Writing to database: $message");
    }
}

class EmailLogger {
    public function log($message) {
        dump("Sending email: $message");

    }
}

// The factory that creates loggers
class LoggerFactory {
    public static function getLogger($type) {
        switch ($type) {
            case 'file':
                return new FileLogger();
            case 'database':
                return new DatabaseLogger();
            case 'email':
                return new EmailLogger();
            default:
                throw new Exception("Invalid logger type");
        }
    }
}

// Usage
$logger = LoggerFactory::getLogger('file');
$logger->log("User logged in");

$urgentLogger = LoggerFactory::getLogger('email');
$urgentLogger->log("System error occurred");