<?php

// Purpose: Composes objects into tree structures to represent part-whole hierarchies, 
// allowing clients to treat individual objects and compositions uniformly.
// Real-world analogy: A file system with folders and files. Both are treated as 
// "items" that can be browsed, but folders can contain other items.


// Component interface
interface FileSystemComponent {
    public function getName();
    public function getSize();
    public function display($indent = 0);
}

// Leaf (File)
class File implements FileSystemComponent {
    private $name;
    private $size;
    
    public function __construct($name, $size) {
        $this->name = $name;
        $this->size = $size;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getSize() {
        return $this->size;
    }
    
    public function display($indent = 0) {
        echo str_repeat("  ", $indent) . "- " . $this->name . " (" . $this->size . " KB)\n";
    }
}

// Composite (Directory)
class Directory implements FileSystemComponent {
    private $name;
    private $components = [];
    
    public function __construct($name) {
        $this->name = $name;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function add(FileSystemComponent $component) {
        $this->components[] = $component;
    }
    
    public function remove(FileSystemComponent $componentToRemove) {
        $this->components = array_filter(
            $this->components,
            function($component) use ($componentToRemove) {
                return $component !== $componentToRemove;
            }
        );
    }
    
    public function getSize() {
        $totalSize = 0;
        foreach ($this->components as $component) {
            $totalSize += $component->getSize();
        }
        return $totalSize;
    }
    
    public function display($indent = 0) {
        echo str_repeat("  ", $indent) . "+ " . $this->name . " (Directory)\n";
        foreach ($this->components as $component) {
            $component->display($indent + 1);
        }
    }
}

// Client code
$root = new Directory("root");

$documents = new Directory("documents");
$documents->add(new File("resume.pdf", 500));
$documents->add(new File("cover_letter.docx", 125));

$pictures = new Directory("pictures");
$pictures->add(new File("vacation.jpg", 2000));
$pictures->add(new File("family.jpg", 1500));

$work = new Directory("work");
$work->add(new File("project_plan.xlsx", 300));

$pictures->add($work); // Subdirectory inside pictures
$root->add($documents);
$root->add($pictures);
$root->add(new File("notes.txt", 10));

// Display the entire file structure
$root->display();
echo "\nTotal size: " . $root->getSize() . " KB\n";

// Display just the pictures directory
echo "\nPictures directory:\n";
$pictures->display();
echo "Pictures size: " . $pictures->getSize() . " KB\n";
?>