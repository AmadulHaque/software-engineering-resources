<?php

// SOLID Principles - Single Responsibility Principle (SRP)
// Each class should have one and only one reason to change.
// This promotes maintainability and readability.

// Other Design Principles Applied:
// - DRY (Don't Repeat Yourself): Avoid redundant code.
// - KISS (Keep It Simple, Stupid): Simplicity leads to better maintainability.
// - OCP (Open/Closed Principle): Classes should be open for extension but closed for modification.

// Order class should ONLY handle order-related functionality.
class Order {
    public function getOrderDetails() {
        // Fetch order details from database or API
    }

    public function calculateTotal() {
        // Calculate the total amount of the order
    }

    public function saveOrder() {
        // Save order details to the database
    }
}

// OrderService class should ONLY be responsible for processing orders.
class OrderService {
    public function processOrder(Order $order) {
        // Process the order using the Order class methods
        $order->getOrderDetails();
        $order->calculateTotal();
        $order->saveOrder();
    }
}

// Additional responsibilities like payment processing and notifications
// should be handled by separate classes to adhere to SRP.

class PaymentService {
    public function processPayment($amount) {
        // Handle payment processing logic
    }
}

class NotificationService {
    public function sendOrderConfirmation($orderId) {
        // Handle sending email or SMS notifications
    }
}
