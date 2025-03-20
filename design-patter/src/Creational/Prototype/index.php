<?php


// This is like having a template document that you copy and modify slightly for different 
// purposes, rather than creating each document from scratch.

abstract class DocumentTemplate {
    protected $title;
    protected $content;
    
    public function __construct($title, $content) {
        $this->title = $title;
        $this->content = $content;
    }
    
    // Clone method - PHP has built-in support with the "clone" keyword
    public function __clone() {
        // Any special cloning logic can go here
        echo "Cloning document...\n";
    }
    
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }
    
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }
    
    public function display() {
        echo "Title: {$this->title}\n";
        echo "Content: {$this->content}\n";
        echo "-----------------------\n";
    }
}

class EmailTemplate extends DocumentTemplate {
    protected $greeting;
    
    public function __construct($title, $content, $greeting) {
        parent::__construct($title, $content);
        $this->greeting = $greeting;
    }
    
    public function setGreeting($greeting) {
        $this->greeting = $greeting;
        return $this;
    }
    
    public function display() {
        echo "Title: {$this->title}\n";
        echo "Greeting: {$this->greeting}\n";
        echo "Content: {$this->content}\n";
        echo "-----------------------\n";
    }
}

// Create a template
$welcomeEmail = new EmailTemplate(
    title: "Welcome to Our Service",
    content: "We're excited to have you join our platform. Here's how to get started...",
    greeting: "Dear Customer"
);

// Use template as prototype for other emails
$johnEmail = clone $welcomeEmail;
$johnEmail->setGreeting("Karim");

$maryEmail = clone $welcomeEmail;
$maryEmail->setGreeting("Halim")
          ->setContent("We're excited to have you join our platform. As a premium member, you get extra features..."); 

// Display all emails
$welcomeEmail->display();
$johnEmail->display();
$maryEmail->display();