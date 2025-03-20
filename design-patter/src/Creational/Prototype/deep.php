<?php

class Address {
    public $city;
    
    public function __construct($city) {
        $this->city = $city;
    }
}

class Person {
    public $name;
    public $address; // Object reference
    
    public function __construct($name, Address $address) {
        $this->name = $name;
        $this->address = $address;
    }

    public function __clone() {
        echo "Deep cloning Person...\n";
        // Clone the Address object separately to ensure a deep copy
        $this->address = clone $this->address;
    }
}

// Create an original object
$originalPerson = new Person("Alice", new Address("New York"));

// Deep copy
$clonedPerson = clone $originalPerson;
$clonedPerson->name = "Bob"; // Changing name is fine
$clonedPerson->address->city = "Los Angeles"; // This does NOT affect the original object

// Display results
echo "Original Person: {$originalPerson->name}, City: {$originalPerson->address->city}\n"; 
echo "Cloned Person: {$clonedPerson->name}, City: {$clonedPerson->address->city}\n"; 
