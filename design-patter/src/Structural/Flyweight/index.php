<?php

// Purpose: Minimizes memory usage by sharing as much data as possible with similar objects.
// Real-world analogy: A text editor that reuses the same letter objects for each occurrence 
// of a letter in a document.

// Flyweight class
class CharacterFlyweight {
    private $symbol;
    private $width;
    private $height;
    private $fontName;
    
    public function __construct($symbol, $fontName) {
        $this->symbol = $symbol;
        $this->fontName = $fontName;
        
        // Width and height are calculated based on the character and font
        $this->width = ord($symbol) % 10 + 5; // Simulate width calculation
        $this->height = 12; // Fixed height for simplicity
        
        echo "Creating flyweight for character '$symbol' with font '$fontName'\n";
    }
    
    // Intrinsic state - shared among all instances
    public function getSymbol() {
        return $this->symbol;
    }
    
    public function getFontName() {
        return $this->fontName;
    }
    
    public function getWidth() {
        return $this->width;
    }
    
    public function getHeight() {
        return $this->height;
    }
    
    // Displays the character with extrinsic state
    public function display($x, $y, $color) {
        echo "Displaying '$this->symbol' at ($x,$y) in $color color using $this->fontName font\n";
    }
}

// Flyweight factory
class CharacterFactory {
    private $characters = [];
    
    public function getCharacter($symbol, $fontName) {
        // Create a unique key for the flyweight
        $key = $symbol . '_' . $fontName;
        
        // If the flyweight doesn't exist, create it
        if (!isset($this->characters[$key])) {
            $this->characters[$key] = new CharacterFlyweight($symbol, $fontName);
        }
        
        return $this->characters[$key];
    }
    
    public function getFlyweightCount() {
        return count($this->characters);
    }
}

// Client code
$factory = new CharacterFactory();

// Simulate rendering text
$text = "Hello World";
$fontName = "Arial";
$x = 10;
$y = 20;
$colors = ["red", "blue", "green", "black"];

echo "Rendering text: $text\n";
for ($i = 0; $i < strlen($text); $i++) {
    $char = $text[$i];
    $flyweight = $factory->getCharacter($char, $fontName);
    
    // Extrinsic state passed to the flyweight
    $color = $colors[$i % count($colors)];
    $charX = $x + ($i * 10);
    $flyweight->display($charX, $y, $color);
}

echo "\nNumber of character flyweights created: " . $factory->getFlyweightCount() . "\n";

// Render more text - reuses existing flyweights
echo "\nRendering more text: World Hello\n";
$text2 = "World Hello";
$y = 40;

for ($i = 0; $i < strlen($text2); $i++) {
    $char = $text2[$i];
    $flyweight = $factory->getCharacter($char, $fontName);
    $color = $colors[($i + 2) % count($colors)];
    $charX = $x + ($i * 10);
    $flyweight->display($charX, $y, $color);
}

echo "\nNumber of character flyweights after second text: " . $factory->getFlyweightCount() . "\n";
