<?php

// SOLID Principles - Interface Segregation Principle (ISP)
// Definition: A class should not be forced to implement interfaces it does not use.
//
// Large interfaces should be broken down into smaller, more specific ones,
// ensuring that classes implement only the methods they need.
//
// Violating ISP - A single interface with unrelated methods

interface Worker {
    public function work();
    public function eat();
}

class HumanWorker implements Worker {
    public function work() {
        echo "Working...";
    }
    
    public function eat() {
        echo "Eating...";
    }
}

class RobotWorker implements Worker {
    public function work() {
        echo "Working...";
    }
    
    public function eat() {
        // Robots do not eat, but they are forced to implement this method
        throw new Exception("Robots do not eat");
    }
}

// Fixing ISP - Segregating interfaces
interface Workable {
    public function work();
}

interface Eatable {
    public function eat();
}

class BetterHumanWorker implements Workable, Eatable {
    public function work() {
        echo "Working...";
    }
    
    public function eat() {
        echo "Eating...";
    }
}

class BetterRobotWorker implements Workable {
    public function work() {
        echo "Working...";
    }
}

// Now, each class implements only the interfaces relevant to them, following ISP.