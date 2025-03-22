<?php

// Purpose: Provides a surrogate or placeholder for another object to control access to it.
// Real-world analogy: A credit card is a proxy for a bank account - it provides access to
// the account without exposing all details.



// Subject interface
interface Image {
    public function display();
}

// RealSubject
class RealImage implements Image {
    private $filename;
    
    public function __construct($filename) {
        $this->filename = $filename;
        $this->loadImageFromDisk();
    }
    
    private function loadImageFromDisk() {
        echo "Loading image from disk: $this->filename\n";
        // This would actually load the image from disk
        // Simulated with a sleep to demonstrate the heavy operation
        sleep(1);
    }
    
    public function display() {
        echo "Displaying image: $this->filename\n";
    }
}

// Proxy
class ProxyImage implements Image {
    private $realImage;
    private $filename;
    
    public function __construct($filename) {
        $this->filename = $filename;
        // Notice: No loading at construction time
    }
    
    public function display() {
        // Create the RealImage only when needed
        if ($this->realImage === null) {
            echo "ProxyImage: Creating RealImage for the first time\n";
            $this->realImage = new RealImage($this->filename);
        } else {
            echo "ProxyImage: Reusing existing RealImage\n";
        }
        
        $this->realImage->display();
    }
}

// Additional Proxy example: Access control
class ProtectedImage implements Image {
    private $realImage;
    private $filename;
    private $userRole;
    
    public function __construct($filename, $userRole) {
        $this->filename = $filename;
        $this->userRole = $userRole;
    }
    
    public function display() {
        if ($this->userRole === "admin") {
            if ($this->realImage === null) {
                $this->realImage = new RealImage($this->filename);
            }
            $this->realImage->display();
        } else {
            echo "Access denied: You don't have permission to view $this->filename\n";
        }
    }
}

// Client code
echo "Testing ProxyImage (lazy loading):\n";
$image1 = new ProxyImage("pic1.jpg");
$image2 = new ProxyImage("pic2.jpg");

// Image is not loaded until display() is called
echo "Will load image 1 now...\n";
$image1->display();

echo "\nWill load image 1 again (should be faster)...\n";
$image1->display();

echo "\nWill load image 2 now...\n";
$image2->display();

echo "\nTesting ProtectedImage (access control):\n";
$image3 = new ProtectedImage("confidential.jpg", "guest");
$image3->display();

$image4 = new ProtectedImage("confidential.jpg", "admin");
$image4->display();
?>