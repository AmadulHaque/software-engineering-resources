<?php

// SOLID Principles - Open/Closed Principle (OCP)
// A class should be open for extension but closed for modification.
// This ensures that we can add new functionality without altering existing code.

// Tricks to achieve OCP:
// - Replace conditionals with polymorphism.
// - Use design patterns like Strategy, Factory, or Decorator.
// - Apply Dependency Inversion to depend on abstractions, not concretions.


// This follows OCP since new discount types can be added without modifying existing code.

// Example: OCP in Payment Processing
// Violation of OCP - Using conditionals to determine payment type
class PaymentService {
    public function processPayment($type, $amount) {
        if ($type === 'bikash') {
            return "Paid $amount via Bikash";
        } elseif ($type === 'nagad') {
            return "Paid $amount via Nagad";
        } elseif ($type === 'roket') {
            return "Paid $amount via Roket";
        } elseif ($type === 'bank') {
            return "Paid $amount via Bank";
        } elseif ($type === 'sslcommerz') {
            return "Paid $amount via SSLCommerz";
        }
        return "Payment method not supported";
    }
}

// Example: OCP in Action (Refactoring Conditionals into Polymorphism)
// Applying OCP: Using Polymorphism for Payment Processing
interface PaymentGateway {
    public function pay($amount);
}

class BikashPayment implements PaymentGateway {
    public function pay($amount) {
        return "Paid $amount via Bikash";
    }
}

class NagadPayment implements PaymentGateway {
    public function pay($amount) {
        return "Paid $amount via Nagad";
    }
}

class RoketPayment implements PaymentGateway {
    public function pay($amount) {
        return "Paid $amount via Roket";
    }
}

class BankPayment implements PaymentGateway {
    public function pay($amount) {
        return "Paid $amount via Bank";
    }
}

class SSLCommerzPayment implements PaymentGateway {
    public function pay($amount) {
        return "Paid $amount via SSLCommerz";
    }
}

class PaymentContext {
    private PaymentGateway $gateway;
    
    public function __construct(PaymentGateway $gateway) {
        $this->gateway = $gateway;
    }
    
    public function processPayment($amount) {
        return $this->gateway->pay($amount);
    }
}

// Example usage:
$payment = new PaymentContext(new BikashPayment());
echo $payment->processPayment(500); // Outputs: Paid 500 via Bikash

$payment = new PaymentContext(new SSLCommerzPayment());
echo $payment->processPayment(1000); // Outputs: Paid 1000 via SSLCommerz

// This follows OCP since new payment gateways can be added without modifying existing code.
