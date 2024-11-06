<?php
session_start(); // Start the session to access cart data

// Include necessary files
require_once 'backend-index.php'; // Include any necessary backend functions

// Check if product ID and quantity are set
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = intval($_POST['product_id']); // Get the product ID from the request
    $quantity = intval($_POST['quantity']); // Get the quantity from the request

    // Validate quantity
    if ($quantity <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid quantity.']);
        exit;
    }

    // Check if the cart session variable is set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = []; // Initialize the cart if it doesn't exist
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$productId])) {
        // If it is, update the quantity
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        // If not, add the product with the specified quantity
        $_SESSION['cart'][$productId] = $quantity;
    }

    // Prepare the response
    $response = [
        'status' => 'success',
        'message' => 'Product added to cart successfully.',
        'cartCount' => count($_SESSION['cart']) // Get the current number of items in the cart
    ];

    // Send the JSON response
    echo json_encode($response);
} else {
    // Handle case where product ID or quantity is not set
    echo json_encode(['status' => 'error', 'message' => 'Product ID and quantity are required.']);
}
?>