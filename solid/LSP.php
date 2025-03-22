<?php

// SOLID Principles - Liskov Substitution Principle (LSP)
// Definition: Objects of a superclass should be replaceable with objects of a subclass without affecting the correctness of the program.

// subclasses can replace superclasses without affecting the program's correctness
// The principle ensures that subclasses behave in a way that does not break the expectations set by the base class.
// If a class is extended, the derived class should be usable without modifying existing functionality or introducing unexpected behaviors.
//
// Checklist:
// - No new exceptions should be thrown in derived classes that are not present in the base class.
// - Pre-conditions cannot be strengthened in derived classes (i.e., a subclass should not impose stricter conditions).
// - Post-conditions cannot be weakened in derived classes (i.e., a subclass should meet or exceed the expectations of the base class).
// - Invariants (fundamental rules governing an object’s state) must be preserved in derived classes.
// - History Constraint: A subclass should not modify the historical behavior of the base class by introducing unexpected changes.
//
// Allowable Adjustments:
// - Contravariant: A subclass can accept broader input types than its parent class.
// - Covariance: A subclass can return a more specific (narrower) type than its parent class.

// Example: Violation of LSP
class Rectangle {
    protected int $width;
    protected int $height;
    
    public function setWidth(int $width) {
        $this->width = $width;
    }
    
    public function setHeight(int $height) {
        $this->height = $height;
    }
    
    public function getArea(): int {
        return $this->width * $this->height;
    }
}

class Square extends Rectangle {
    public function setWidth(int $width) {
        $this->width = $this->height = $width;
    }
    
    public function setHeight(int $height) {
        $this->width = $this->height = $height;
    }
}

// The Square class violates LSP because it alters the behavior of setWidth() and setHeight(),
// making it incompatible with the expectations set by the Rectangle class.

// Correcting LSP by using Composition instead of Inheritance
interface Shape {
    public function getArea(): int;
}

class CorrectRectangle implements Shape {
    protected int $width;
    protected int $height;
    
    public function __construct(int $width, int $height) {
        $this->width = $width;
        $this->height = $height;
    }
    
    public function getArea(): int {
        return $this->width * $this->height;
    }
}

class CorrectSquare implements Shape {
    protected int $side;
    
    public function __construct(int $side) {
        $this->side = $side;
    }
    
    public function getArea(): int {
        return $this->side * $this->side;
    }
}

// Now, both CorrectRectangle and CorrectSquare can be used interchangeably as Shape without breaking LSP.
