<?php


// Imagine you're designing a house. You can choose a "modern style" or "rustic style" 
// for everything (doors, windows, furniture). Each style has its own factory that creates 
// matching pieces.


// Products - Buttons
interface Button {
    public function render();
}

class BlueButton implements Button {
    public function render() {
        echo "<button style='background: blue; color: white;'>Click Me</button>\n";
    }
}

class RedButton implements Button {
    public function render() {
        echo "<button style='background: red; color: white;'>Click Me</button>\n";
    }
}

// Products - Input fields
interface InputField {
    public function render();
}

class BlueInputField implements InputField {
    public function render() {
        echo "<input style='border-color: blue;' type='text'>\n";
    }
}

class RedInputField implements InputField {
    public function render() {
        echo "<input style='border-color: red;' type='text'>\n";
    }
}

// Abstract Factory
interface ThemeFactory {
    public function createButton();
    public function createInputField();
}

// Concrete Factories
class BlueThemeFactory implements ThemeFactory {
    public function createButton() {
        return new BlueButton();
    }
    
    public function createInputField() {
        return new BlueInputField();
    }
}

class RedThemeFactory implements ThemeFactory {
    public function createButton() {
        return new RedButton();
    }
    
    public function createInputField() {
        return new RedInputField();
    }
}

// Usage
function createForm(ThemeFactory $factory) {
    echo "<form>\n";
    
    $button = $factory->createButton();
    $input = $factory->createInputField();
    
    $input->render();
    $button->render();
    
    echo "</form>\n";
}

// Create a blue-themed form
$blueFactory = new BlueThemeFactory();
createForm($blueFactory);

// Create a red-themed form
$redFactory = new RedThemeFactory();
createForm($redFactory);