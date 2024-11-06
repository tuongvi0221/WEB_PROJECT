<?php
session_start();

// Sample function to get product details
function getProductDetails($productId) {
    // Normally fetch product details from the database
    return [
        'name' => "Product $productId",
        'price' => 100.00 // Example fixed price
    ];
}

// Function to update quantity in the cart
if (isset($_POST['productId']) && isset($_POST['action'])) {
    $productId = $_POST['productId'];
    $action = $_POST['action'];

    if ($action === 'increment') {
        $_SESSION['cart'][$productId]['quantity']++;
    } elseif ($action === 'decrement' && $_SESSION['cart'][$productId]['quantity'] > 1) {
        $_SESSION['cart'][$productId]['quantity']--;
    }

    // Response with updated cart HTML and total
    echo json_encode([
        'cartHtml' => generateCartHtml($_SESSION['cart']),
        'total' => calculateTotal($_SESSION['cart'])
    ]);
    exit;
}

// Function to apply a discount code
if (isset($_POST['discountCode'])) {
    $code = $_POST['discountCode'];
    $discount = applyDiscount($code);

    if ($discount > 0) {
        $_SESSION['discount'] = $discount;
        $message = "Discount applied!";
    } else {
        $message = "Invalid discount code.";
    }

    echo json_encode([
        'message' => $message,
        'newTotal' => calculateTotal($_SESSION['cart'], $_SESSION['discount'])
    ]);
    exit;
}

// Calculate total with optional discount
function calculateTotal($cart, $discount = 0) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return number_format($total * (1 - $discount), 2);
}

// Apply discount code
function applyDiscount($code) {
    $validDiscounts = ['SAVE10' => 0.1, 'SAVE20' => 0.2];
    return $validDiscounts[$code] ?? 0;
}

// Function to generate cart HTML for AJAX response
function generateCartHtml($cart) {
    ob_start();
    foreach ($cart as $productId => $item) {
        echo "<div class='cart-item'>";
        echo "<span>{$item['name']}</span>";
        echo "<button onclick='updateQuantity($productId, \"decrement\")'>-</button>";
        echo "<input type='text' value='{$item['quantity']}' readonly>";
        echo "<button onclick='updateQuantity($productId, \"increment\")'>+</button>";
        echo "<span>{$item['price']}</span>";
        echo "</div>";
    }
    return ob_get_clean();
}
?>