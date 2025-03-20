<?php

// Think of this like ordering a custom sandwich. Instead of one big complicated order,
// you build it step by step: bread type, then meat, then cheese, then toppings, etc.

// The Builder pattern is used to construct complex objects step by step. It allows you to create an object by defining a type and a set of steps to follow. The Builder pattern is often used in conjunction with the Factory pattern to create objects that have a complex construction process.
// The Builder pattern is useful when you need to create an object that has multiple optional components. By using the Builder pattern, you can define a set of steps that can be followed to create the object, and then you can choose which steps to follow to create the object with the desired components.

// Product class with immutable properties
class Product {
    private $id;
    private $name;
    private $price;
    private $description;
    private $category;
    private $weight;
    private $dimensions;
    private $imageUrl;
    private $inStock;
    private $featuredProduct;

    // Constructor accepts only the builder
    public function __construct(ProductBuilder $builder) {
        // Copy all properties from the builder
        $this->id = $builder->getId();
        $this->name = $builder->getName();
        $this->price = $builder->getPrice();
        $this->description = $builder->getDescription();
        $this->category = $builder->getCategory();
        $this->weight = $builder->getWeight();
        $this->dimensions = $builder->getDimensions();
        $this->imageUrl = $builder->getImageUrl();
        $this->inStock = $builder->getInStock();
        $this->featuredProduct = $builder->getFeaturedProduct();
    }

    // Only getters, no setters - for immutability
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getPrice() { return $this->price; }
    public function getDescription() { return $this->description; }
    public function getCategory() { return $this->category; }
    public function getWeight() { return $this->weight; }
    public function getDimensions() { return $this->dimensions; }
    public function getImageUrl() { return $this->imageUrl; }
    public function getInStock() { return $this->inStock; }
    public function getFeaturedProduct() { return $this->featuredProduct; }

    // Display product details
    public function display() {
        echo "Product: {$this->name} (ID: {$this->id})\n";
        echo "Price: \${$this->price}\n";
        echo "Category: {$this->category}\n";
        echo "Description: {$this->description}\n";
        echo "Weight: {$this->weight}kg\n";
        echo "Dimensions: {$this->dimensions}\n";
        echo "Image: {$this->imageUrl}\n";
        echo "In Stock: " . ($this->inStock ? "Yes" : "No") . "\n";
        echo "Featured: " . ($this->featuredProduct ? "Yes" : "No") . "\n";
        echo "------------------------\n";
    }
}

// Builder class
class ProductBuilder {
    private $id;
    private $name;
    private $price;
    private $description = '';
    private $category = 'Uncategorized';
    private $weight = 0;
    private $dimensions = 'N/A';
    private $imageUrl = 'default.jpg';
    private $inStock = true;
    private $featuredProduct = false;

    // Required properties in constructor
    public function __construct($id, $name, $price) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    // Optional properties with fluent interface
    public function withDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function withCategory($category) {
        $this->category = $category;
        return $this;
    }

    public function withWeight($weight) {
        $this->weight = $weight;
        return $this;
    }

    public function withDimensions($dimensions) {
        $this->dimensions = $dimensions;
        return $this;
    }

    public function withImageUrl($imageUrl) {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    public function setInStock($inStock) {
        $this->inStock = $inStock;
        return $this;
    }

    public function setFeatured($featured) {
        $this->featuredProduct = $featured;
        return $this;
    }

    // Build method creates the immutable Product
    public function build() {
        return new Product($this);
    }

    // Getters for Product constructor
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getPrice() { return $this->price; }
    public function getDescription() { return $this->description; }
    public function getCategory() { return $this->category; }
    public function getWeight() { return $this->weight; }
    public function getDimensions() { return $this->dimensions; }
    public function getImageUrl() { return $this->imageUrl; }
    public function getInStock() { return $this->inStock; }
    public function getFeaturedProduct() { return $this->featuredProduct; }
}

// Director class for common product configurations
class ProductDirector {
    public function createBasicProduct($id, $name, $price) {
        return (new ProductBuilder($id, $name, $price))
            ->build();
    }

    public function createFeaturedProduct($id, $name, $price, $description, $imageUrl) {
        return (new ProductBuilder($id, $name, $price))
            ->withDescription($description)
            ->withImageUrl($imageUrl)
            ->setFeatured(true)
            ->build();
    }

    public function createPhysicalProduct($id, $name, $price, $weight, $dimensions) {
        return (new ProductBuilder($id, $name, $price))
            ->withWeight($weight)
            ->withDimensions($dimensions)
            ->withCategory('Physical Goods')
            ->build();
    }

    public function createDigitalProduct($id, $name, $price, $description) {
        return (new ProductBuilder($id, $name, $price))
            ->withDescription($description)
            ->withWeight(0)
            ->withDimensions('Digital')
            ->withCategory('Digital Products')
            ->build();
    }
}

// Usage example
// 1. Create a Director
$director = new ProductDirector();

// 2. Use the Director to create common product types
$basicProduct = $director->createBasicProduct(101, "Basic T-shirt", 19.99);
$featuredProduct = $director->createFeaturedProduct(
    102, 
    "Limited Edition Shoes", 
    149.99, 
    "Exclusive commemorative edition with custom design", 
    "limited_shoes.jpg"
);
$physicalBook = $director->createPhysicalProduct(
    103, 
    "PHP Design Patterns Guide", 
    29.99, 
    0.5, 
    "21cm x 15cm x 2cm"
);
$digitalBook = $director->createDigitalProduct(
    104, 
    "PHP Design Patterns eBook", 
    19.99, 
    "Digital version of our best-selling design patterns book"
);

// 3. You can also use the Builder directly for complete customization
$customProduct = (new ProductBuilder(105, "Custom Gaming Chair", 299.99))
    ->withDescription("Ergonomic gaming chair with adjustable features")
    ->withCategory("Furniture")
    ->withWeight(15.5)
    ->withDimensions("60cm x 70cm x 120cm")
    ->withImageUrl("gaming_chair.jpg")
    ->setInStock(false)
    ->build();

// Display all products
$basicProduct->display();
$featuredProduct->display();
$physicalBook->display();
$digitalBook->display();
$customProduct->display();

// Attempting to modify a product won't work because it's immutable
// The following would cause an error if uncommented:
// $basicProduct->price = 29.99; // Error: Cannot access private property
?>