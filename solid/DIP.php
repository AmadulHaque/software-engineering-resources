<?php

// SOLID Principles - Dependency Inversion Principle (DIP)
// Definition: High-level modules should not depend on low-level modules. Both should depend on abstractions.
// Abstractions should not depend on details. Details should depend on abstractions.
//
// Violating DIP - Direct dependency on a low-level module
class PayPalPayment {
    public function pay($amount) {
        echo "Paying $$amount via PayPal.";
    }
}

class PaymentProcessor {
    private PayPalPayment $paymentMethod;
    
    public function __construct(PayPalPayment $paymentMethod) {
        $this->paymentMethod = $paymentMethod;
    }
    
    public function processPayment($amount) {
        $this->paymentMethod->pay($amount);
    }
}

// Fixing DIP - Introduce an abstraction
interface PaymentMethod {
    public function pay($amount);
}

class PayPal implements PaymentMethod {
    public function pay($amount) {
        echo "Paying $$amount via PayPal.";
    }
}

class Stripe implements PaymentMethod {
    public function pay($amount) {
        echo "Paying $$amount via Stripe.";
    }
}

class ImprovedPaymentProcessor {
    private PaymentMethod $paymentMethod;
    
    public function __construct(PaymentMethod $paymentMethod) {
        $this->paymentMethod = $paymentMethod;
    }
    
    public function processPayment($amount) {
        $this->paymentMethod->pay($amount);
    }
}

// Example usage:
$processor = new ImprovedPaymentProcessor(new PayPal());
$processor->processPayment(100);

$processor = new ImprovedPaymentProcessor(new Stripe());
$processor->processPayment(200);

// Now, high-level modules depend on abstractions, not concrete implementations, adhering to DIP.