<?php 
session_start();
require_once 'cart_functions.php'; // This file contains functions for managing cart actions

// Calculate total price including any applied discounts
$totalPrice = calculateTotal($_SESSION['cart'] ?? [], $_SESSION['discount'] ?? 0);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to existing project styles -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> <!-- jQuery for Ajax -->
</head>

<body>

    <div class="container">
        <h2>Your Cart</h2>

        <form id="cart-form">
            <?php if (!empty($_SESSION['cart'])): ?>
            <!-- Loop through cart items and display each one with controls -->
            <?php foreach ($_SESSION['cart'] as $productId => $item): ?>
            <div class="cart-item">
                <!-- Checkbox for selecting multiple items -->
                <input type="checkbox" class="select-item" data-id="<?= $productId ?>">

                <!-- Product details -->
                <span><?= getProductDetails($productId)['name'] ?></span>

                <!-- Quantity controls with increment and decrement -->
                <button type="button" onclick="updateQuantity(<?= $productId ?>, 'decrement')">-</button>
                <input type="text" value="<?= $item['quantity'] ?>" readonly>
                <button type="button" onclick="updateQuantity(<?= $productId ?>, 'increment')">+</button>

                <!-- Product Price -->
                <span><?= number_format($item['price'], 2) ?></span>

                <!-- Total Price for this item -->
                <span class="item-total"><?= number_format($item['price'] * $item['quantity'], 2) ?></span>
            </div>
            <?php endforeach; ?>

            <!-- Discount Code Section -->
            <div class="discount-section">
                <input type="text" id="discount-code" placeholder="Enter discount code">
                <button type="button" onclick="applyDiscount()">Apply</button>
                <p id="discount-message"></p>
            </div>

            <!-- Total Price Display -->
            <div id="total-price">Total: <?= number_format($totalPrice, 2) ?></div>

            <!-- Checkout Button and Confirmation Modal Trigger -->
            <button id="checkout-btn" onclick="confirmOrder()">Checkout</button>
            <?php else: ?>
            <p>Your cart is empty.</p>
            <?php endif; ?>
        </form>
    </div>

    <!-- Confirmation Popup Modal -->
    <div id="confirmation-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Confirm Your Order</h3>
            <p id="order-summary">Order Summary will appear here...</p>
            <button onclick="placeOrder()">Confirm</button>
        </div>
    </div>

    <script>
    // Function to handle quantity increment/decrement with Ajax
    function updateQuantity(productId, action) {
        $.post('cart_functions.php', {
            productId: productId,
            action: action
        }, function(response) {
            $('#cart-form').html(response.cartHtml);
            $('#total-price').text('Total: ' + response.total);
        }, 'json');
    }

    // Apply Discount Code
    function applyDiscount() {
        const discountCode = $('#discount-code').val();
        $.post('cart_functions.php', {
            discountCode
        }, function(response) {
            $('#discount-message').text(response.message);
            $('#total-price').text('Total: ' + response.newTotal);
        }, 'json');
    }

    // Show Confirmation Modal for Checkout
    function confirmOrder() {
        $('#confirmation-modal').show();
        $.post('order_summary.php', function(summary) {
            $('#order-summary').html(summary);
        });
    }

    // Close the Confirmation Modal
    function closeModal() {
        $('#confirmation-modal').hide();
    }

    // Finalize and place the order
    function placeOrder() {
        window.location.href = 'order.php?q=multi'; // Redirect to the order page
    }
    </script>

</body>

</html>