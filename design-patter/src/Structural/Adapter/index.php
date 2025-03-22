<?php

// Purpose: Allows incompatible interfaces to work together by creating a "wrapper"
//  that translates one interface into another.
// Real-world analogy: It's like a power adapter that lets you plug a US device into
//  a European outlet.



// The interface our application expects
interface PaymentProcessor {
    public function processPayment($amount);
}

// External payment service with incompatible interface
class PayPalAPI {
    public function makePayment($dollars) {
        echo "Processing $$dollars via PayPal\n";
        return true;
    }
}

// External payment service with different interface
class StripeAPI {
    public function chargeAmount($amountInCents, $currency = 'usd') {
        $dollars = $amountInCents / 100;
        echo "Processing $$dollars via Stripe\n";
        return 'success';
    }
}

// Adapter for PayPal
class PayPalAdapter implements PaymentProcessor {
    private $paypal;
    
    public function __construct(PayPalAPI $paypal) {
        $this->paypal = $paypal;
    }
    
    public function processPayment($amount) {
        // Convert to format PayPal API expects
        return $this->paypal->makePayment($amount);
    }
}

// Adapter for Stripe
class StripeAdapter implements PaymentProcessor {
    private $stripe;
    
    public function __construct(StripeAPI $stripe) {
        $this->stripe = $stripe;
    }
    
    public function processPayment($amount) {
        // Convert to format Stripe API expects (cents)
        $amountInCents = $amount * 100;
        return $this->stripe->chargeAmount($amountInCents) === 'success';
    }
}



// Usage
$paypal = new PayPalAdapter(new PayPalAPI());
$stripe = new StripeAdapter(new StripeAPI());


$paypal->processPayment(99.99);
$stripe->processPayment(49.99);
