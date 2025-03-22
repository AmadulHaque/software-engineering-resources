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
        echo "Shallow cloning Person...\n";
    }
}

// Create an original object
$originalPerson = new Person("Karim", new Address("Dhaka"));

// Shallow copy (cloned object shares the same address object)
$clonedPerson = clone $originalPerson;
$clonedPerson->name = "Halim"; 
$clonedPerson->address->city = "Barishal"; // This affects the original object too

// Display results
echo "Original Person: {$originalPerson->name}, City: {$originalPerson->address->city}\n"; 
echo "Cloned Person: {$clonedPerson->name}, City: {$clonedPerson->address->city}\n"; 
