<?php

// Purpose: Attaches additional responsibilities to objects dynamically, providing a flexible
//  alternative to subclassing for extending functionality.

// Real-world analogy: Think of a coffee shop where you can add different toppings 
// to a basic coffee.


// Component interface
interface Coffee {
    public function getCost();
    public function getDescription();
}

// Concrete Component
class SimpleCoffee implements Coffee {
    public function getCost() {
        return 5.00;
    }
    
    public function getDescription() {
        return "Simple coffee";
    }
}

// Base Decorator
abstract class CoffeeDecorator implements Coffee {
    protected $decoratedCoffee;
    
    public function __construct(Coffee $coffee) {
        $this->decoratedCoffee = $coffee;
    }
    
    public function getCost() {
        return $this->decoratedCoffee->getCost();
    }
    
    public function getDescription() {
        return $this->decoratedCoffee->getDescription();
    }
}

// Concrete Decorators
class MilkDecorator extends CoffeeDecorator {
    public function getCost() {
        return $this->decoratedCoffee->getCost() + 1.00;
    }
    
    public function getDescription() {
        return $this->decoratedCoffee->getDescription() . ", with milk";
    }
}

class WhipDecorator extends CoffeeDecorator {
    public function getCost() {
        return $this->decoratedCoffee->getCost() + 1.50;
    }
    
    public function getDescription() {
        return $this->decoratedCoffee->getDescription() . ", with whipped cream";
    }
}

class VanillaDecorator extends CoffeeDecorator {
    public function getCost() {
        return $this->decoratedCoffee->getCost() + 2.00;
    }
    
    public function getDescription() {
        return $this->decoratedCoffee->getDescription() . ", with vanilla";
    }
}

// Client code
function showCoffee(Coffee $coffee) {
    echo $coffee->getDescription() . " costs $" . $coffee->getCost() . "\n";
}

$simpleCoffee = new SimpleCoffee();
showCoffee($simpleCoffee);

$milkCoffee = new MilkDecorator($simpleCoffee);
showCoffee($milkCoffee);

$whipMilkCoffee = new WhipDecorator($milkCoffee);
showCoffee($whipMilkCoffee);

// You can also chain decorators directly
$fancyCoffee = new VanillaDecorator(new WhipDecorator(new MilkDecorator(new SimpleCoffee())));
showCoffee($fancyCoffee);
